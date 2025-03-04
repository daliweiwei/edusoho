<?php

namespace Codeages\Biz\ItemBank\Item\Service\Impl;

use Codeages\Biz\Framework\Event\Event;
use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\ItemBank\BaseService;
use Codeages\Biz\ItemBank\ErrorCode;
use Codeages\Biz\ItemBank\Item\Dao\ItemDao;
use Codeages\Biz\ItemBank\Item\Dao\QuestionDao;
use Codeages\Biz\ItemBank\Item\Exception\ItemException;
use Codeages\Biz\ItemBank\Item\ItemParser;
use Codeages\Biz\ItemBank\Item\Service\AttachmentService;
use Codeages\Biz\ItemBank\Item\Service\ItemCategoryService;
use Codeages\Biz\ItemBank\Item\Service\ItemService;
use Codeages\Biz\ItemBank\Item\Type\ChoiceItem;
use Codeages\Biz\ItemBank\Item\Type\DetermineItem;
use Codeages\Biz\ItemBank\Item\Type\EssayItem;
use Codeages\Biz\ItemBank\Item\Type\FillItem;
use Codeages\Biz\ItemBank\Item\Type\Item;
use Codeages\Biz\ItemBank\Item\Type\MaterialItem;
use Codeages\Biz\ItemBank\Item\Type\SingleChoiceItem;
use Codeages\Biz\ItemBank\Item\Type\UncertainChoiceItem;
use Codeages\Biz\ItemBank\Item\Wrapper\ExportItemsWrapper;
use Codeages\Biz\ItemBank\ItemBank\Exception\ItemBankException;
use Codeages\Biz\ItemBank\ItemBank\Service\ItemBankService;
use ExamParser\Writer\WriteDocx;

class ItemServiceImpl extends BaseService implements ItemService
{
    public function createItem($item, $isBatch = false)
    {
        if (empty($item['type'])) {
            throw new ItemException('Item without type', ErrorCode::ITEM_ARGUMENT_INVALID);
        }
        $arguments = $item;
        $item = $this->getItemProcessor($item['type'])->process($item);
        $item['created_user_id'] = empty($this->biz['user']['id']) ? 0 : $this->biz['user']['id'];
        $item['updated_user_id'] = $item['created_user_id'];
        $questions = $item['questions'];
        unset($item['questions']);

        $this->beginTransaction();
        try {
            $item = $this->getItemDao()->create($item);
            if (!empty($arguments['attachments'])) {
                $attachments = $this->sortAttachments($arguments['attachments']);
                $this->updateAttachments($attachments, $item['id'], AttachmentService::ITEM_TYPE);
            }

            $this->createQuestions($item['id'], $questions);

            if (!empty($item['category_id']) && 0 < $item['category_id']) {
                $this->getItemCategoryService()->updateItemNumAndQuestionNum($item['category_id']);
            }

            if (!$isBatch) {
                $this->getItemBankService()->updateItemNumAndQuestionNum($item['bank_id']);
//                $this->dispatch('item.create', $item, ['argument' => $arguments]);
                $this->dispatchEvent('item.create', new Event($item, ['argument' => $arguments]));
            }

            $this->commit();

            return $item;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function importItems($items, $bankId)
    {
        $savedItems = [];

        try {
            $this->beginTransaction();
            foreach ($items as $item) {
                $item['bank_id'] = $bankId;
                $savedItem = $this->createItem($item, true);
                $savedItems[] = array_merge($savedItems, $savedItem);
            }

            $this->getItemBankService()->updateItemNumAndQuestionNum($bankId);
            $this->getItemCategoryService()->buildItemNumAndQuestionNumBybankId($bankId);
            $this->dispatchEvent('item.import', new Event($savedItems));
            $this->commit();

            return $savedItems;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function readWordFile($wordPath, $resourcePath = '')
    {
        $options = [];
        if (!empty($resourcePath)) {
            $options = ['resourceTmpPath' => $resourcePath];
        }

        return $this->getItemParser()->read($wordPath, $options);
    }

    public function parseItems($text)
    {
        return $this->getItemParser()->parse($text);
    }

    public function updateItem($id, $item)
    {
        $originItem = $this->getItemWithQuestions($id);
        if (empty($originItem)) {
            throw new ItemException('Item not found', ErrorCode::ITEM_NOT_FOUND);
        }
        $arguments = $item;
        $item = $this->getItemProcessor($originItem['type'])->process($item);
        $item['updated_user_id'] = empty($this->biz['user']['id']) ? 0 : $this->biz['user']['id'];
        $questions = $item['questions'];
        unset($item['questions']);

        $this->beginTransaction();
        try {
            $this->updateQuestions($id, $questions);

            $item['question_num'] = $this->getQuestionDao()->count(['item_id' => $id]);
            $item = $this->getItemDao()->update($id, $item);
            if (!empty($arguments['attachments'])) {
                $attachments = $this->sortAttachments($arguments['attachments']);
                $this->updateAttachments($attachments, $id, AttachmentService::ITEM_TYPE);
            }

            $this->getItemBankService()->updateItemNumAndQuestionNum($item['bank_id']);

            if ($originItem['category_id'] != $item['category_id']) {
                if (0 < $originItem['category_id']) {
                    $this->getItemCategoryService()->updateItemNumAndQuestionNum($originItem['category_id']);
                }
                if (0 < $item['category_id']) {
                    $this->getItemCategoryService()->updateItemNumAndQuestionNum($item['category_id']);
                }
            } else {
                if (0 < $originItem['category_id']) {
                    $this->getItemCategoryService()->updateItemNumAndQuestionNum($item['category_id']);
                }
            }
            $this->dispatchEvent('item.update', new Event($item, ['argument' => $arguments, 'originItem' => $originItem]));
//            $this->dispatch('item.update', $item, ['argument' => $arguments, 'originItem' => $originItem]);

            $this->commit();

            return $item;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function getItem($id)
    {
        return $this->getItemDao()->get($id);
    }

    public function getItemIncludeDeleted($id)
    {
        return $this->getItemDao()->getIncludeDeleted($id);
    }

    public function getItemWithQuestions($id, $withAnswer = false)
    {
        $item = $this->getItem($id);
        if (empty($item)) {
            return [];
        }

        $item['questions'] = $this->findQuestionsByItemId($item['id']);
        $item = $this->biz['item_wrapper']->wrap($item, $withAnswer);
        $item = $this->biz['item_attachment_wrapper']->wrap($item);

        return $item;
    }

    public function findItemsByIds($ids, $withQuestions = false)
    {
        $items = $this->getItemDao()->findByIds($ids);
        if ($withQuestions) {
            $questions = $this->findQuestionsByItemIds(ArrayToolkit::column($items, 'id'));
            $questions = ArrayToolkit::group($questions, 'item_id');
            foreach ($items as &$item) {
                $item['questions'] = empty($questions[$item['id']]) ? [] : $questions[$item['id']];
            }
        }
        $that = $this;
        array_walk($items, function (&$item) use ($that) {
            $item['includeImg'] = $that->hasImg($item['material']);
            $item = $this->biz['item_attachment_wrapper']->wrap($item);
        });

        return ArrayToolkit::index($items, 'id');
    }

    public function findItemsByIdsIncludeDeleted($ids, $withQuestions = false)
    {
        $items = $this->getItemDao()->findByIdsIncludeDeleted($ids);
        if ($withQuestions) {
            $questions = $this->getQuestionDao()->findByItemsIdsIncludeDeleted(array_column($items, 'id'));
            $questions = ArrayToolkit::group($questions, 'item_id');
            foreach ($items as &$item) {
                $item['questions'] = empty($questions[$item['id']]) ? [] : $questions[$item['id']];
            }
        }
        $that = $this;
        array_walk($items, function (&$item) use ($that) {
            $item['includeImg'] = $that->hasImg($item['material']);
            $item = $that->biz['item_attachment_wrapper']->wrap($item);
        });

        return ArrayToolkit::index($items, 'id');
    }

    public function searchItems($conditions, $orderBys, $start, $limit, $columns = [])
    {
        $conditions = $this->filterItemConditions($conditions);

        $items = $this->getItemDao()->search($conditions, $orderBys, $start, $limit, $columns);

        if (empty($columns) || in_array('material', $columns)) {
            $that = $this;
            array_walk($items, function (&$item) use ($that) {
                $item['includeImg'] = $that->hasImg($item['material']);
            });
        }

        return $items;
    }

    public function searchItemsIncludeDeleted($conditions, $orderBys, $start, $limit, $columns = [])
    {
        return $this->getItemDao()->searchIncludeDeleted($conditions, $orderBys, $start, $limit, $columns);
    }

    public function countItems($conditions)
    {
        $conditions = $this->filterItemConditions($conditions);

        return $this->getItemDao()->count($conditions);
    }

    public function getItemCountGroupByTypes($conditions)
    {
        $conditions = $this->filterItemConditions($conditions);

        return $this->getItemDao()->getItemCountGroupByTypes($conditions);
    }

    public function findItemsByCategoryIds($categoryIds)
    {
        return $this->getItemDao()->findByCategoryIds($categoryIds);
    }

    public function deleteItem($id, $isBatch = false)
    {
        $item = $this->getItem($id);
        if (empty($item)) {
            return false;
        }
        try {
            $this->beginTransaction();

            $result = $this->getItemDao()->delete($id);
            $this->getAttachmentService()->batchDeleteAttachment(['target_id' => $id, 'target_type' => 'item']);

            $this->deleteQuestions(['item_id' => $id]);

            $this->getItemBankService()->updateItemNumAndQuestionNum($item['bank_id']);

            if (0 < $item['category_id']) {
                $this->getItemCategoryService()->updateItemNumAndQuestionNum($item['category_id']);
            }

            if (!$isBatch) {
//                $this->dispatch('item.delete', $item);
                $this->dispatchEvent('item.delete', new Event($item));
            }

            $this->commit();

            return $result;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function deleteItems($ids)
    {
        if (empty($ids)) {
            return false;
        }

        $deleteItems = $this->findItemsByIds($ids);
        foreach ($ids as $id) {
            $this->deleteItem($id, true);
        }

//        $this->dispatch('item.batchDelete', $deleteItems);
        $this->dispatchEvent('item.batchDelete', new Event($deleteItems));

        return true;
    }

    public function updateItemsCategoryId($ids, $categoryId)
    {
        if (empty($ids)) {
            return [];
        }

        $updateFields = [];
        foreach ($ids as $id) {
            $updateFields[] = ['category_id' => $categoryId];
        }

        try {
            $this->beginTransaction();

            $this->getItemDao()->batchUpdate($ids, $updateFields, 'id');

            $item = $this->getItem($ids[0]);

            $this->getItemCategoryService()->buildItemNumAndQuestionNumBybankId($item['bank_id']);

//            $this->dispatch('item.update_category', $ids, ['categoryId' => $categoryId]);
            $this->dispatchEvent('item.update_category', new Event($ids, ['categoryId' => $categoryId]));
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function review($itemResponses)
    {
        $reviewResults = [];

        $items = $this->getItemDao()->findByIdsIncludeDeleted(array_column($itemResponses, 'item_id'));
        $items = ArrayToolkit::index($items, 'id');
        foreach ($itemResponses as $itemResponse) {
            $itemType = empty($items[$itemResponse['item_id']]['type']) ? ChoiceItem::TYPE : $items[$itemResponse['item_id']]['type'];
            $reviewResults[] = $this->getItemProcessor($itemType)->review(
                $itemResponse['item_id'],
                $itemResponse['question_responses']
            );
        }

        return $reviewResults;
    }

    public function exportItems($bankId, $conditions, $path, $imgRootDir)
    {
        if (empty($this->getItemBankService()->getItemBank($bankId))) {
            throw new ItemBankException('Item bank not found.', ErrorCode::ITEM_BANK_NOT_FOUND);
        }

        $conditions['bank_id'] = $bankId;
        if (!empty($conditions['category_id']) && $conditions['category_id'] !== 0) {
            $conditions['category_ids'] = $this->getItemCategoryService()->findCategoryChildrenIds($conditions['category_id']);
            $conditions['category_ids'][] = $conditions['category_id'];
            unset($conditions['category_id']);
        }
        $items = $this->searchItems($conditions, ['created_time' => 'DESC'], 0, $this->countItems($conditions));
        if (empty($items)) {
            return false;
        }
        $items = $this->getExportItemsWrapper($imgRootDir)->wrap($items);

        $writer = new WriteDocx($path);
        $writer->write($items);

        return true;
    }

    public function findQuestionsByQuestionIds($questionIds)
    {
        $questions = $this->getQuestionDao()->findQuestionsByQuestionIds($questionIds);

        return ArrayToolkit::index($questions, 'id');
    }

    public function findQuestionsByQuestionIdsIncludeDeleted($questionIds)
    {
        $questions = $this->getQuestionDao()->findQuestionsByQuestionIdsIncludeDeleted($questionIds);

        return ArrayToolkit::index($questions, 'id');
    }

    public function countQuestionsByBankId($bankId)
    {
        return $this->getItemDao()->countItemQuestionNumByBankId($bankId);
    }

    public function countQuestionsByCategoryId($categoryId)
    {
        return $this->getItemDao()->countItemQuestionNumByCategoryId($categoryId);
    }

    public function searchQuestions($conditions, $orderBys, $start, $limit, $columns = [])
    {
        return $this->getQuestionDao()->search($conditions, $orderBys, $start, $limit, $columns);
    }

    protected function createQuestions($itemId, $questions)
    {
        if (empty($questions)) {
            return;
        }
        $questionAttachments = [];
        foreach ($questions as $question) {
            $question['item_id'] = $itemId;
            $question['created_user_id'] = empty($this->biz['user']['id']) ? 0 : $this->biz['user']['id'];
            $question['updated_user_id'] = $question['created_user_id'];
            if(!empty($question['attachments'])) {
                $attachments = $this->sortAttachments($question['attachments']);
            }
            unset($question['attachments']);
            $itemQuestion = $this->getQuestionDao()->create($question);
            if (!empty($attachments)) {
                $this->updateAttachments($attachments, $itemQuestion['id'], AttachmentService::QUESTION_TYPE);
            }
        }
    }

    protected function updateQuestions($itemId, $questions)
    {
        if (empty($questions)) {
            return;
        }
        $originQuestionIds = array_column($this->findQuestionsByItemId($itemId), 'id');
        $updateQuestions = [];
        $questionAttachments = [];
        foreach ($questions as $key => $question) {
            if (empty($question['id'])) {
                continue;
            }
            if (in_array($question['id'], $originQuestionIds)) {
                $question['updated_user_id'] = empty($this->biz['user']['id']) ? 0 : $this->biz['user']['id'];
                if(!empty($question['attachments'])) {
                    $attachments = $this->sortAttachments($question['attachments']);
                    $questionAttachments[] = ['id' => $question['id'], 'attachments' => $attachments];
                }
                unset($question['attachments']);

                $updateQuestions[] = $question;
                unset($questions[$key]);
            }
        }
        $this->createQuestions($itemId, $questions);
        $updateQuestionIds = array_column($updateQuestions, 'id');
        if (!empty($updateQuestionIds)) {
            $this->getQuestionDao()->batchUpdate($updateQuestionIds, $updateQuestions);
            $this->updateQuestionAttachments($questionAttachments);
        }
        $deleteQuestions = array_diff($originQuestionIds, $updateQuestionIds);
        if (!empty($deleteQuestions)) {
            $this->deleteQuestions(['ids' => $deleteQuestions]);
        }
    }

    protected function updateQuestionAttachments($questions)
    {
        foreach ($questions as $question) {
            if (!empty($question['attachments'])) {
                $this->updateAttachments($question['attachments'], $question['id'], AttachmentService::QUESTION_TYPE);
            }
        }
    }

    protected function sortAttachments($sortAttachments)
    {
        $attachments = [];
        $seq = 1;
        foreach ($sortAttachments as $sortAttachment) {
            $sortAttachment['seq'] = $seq;
            $attachments[] = $sortAttachment;
            ++$seq;
        }

        return $attachments;
    }

    protected function updateAttachments($attachments, $targetId, $targetType)
    {
        foreach ($attachments as $attachment) {
            $this->getAttachmentService()->updateAttachment($attachment['id'], [
                'target_id' => $targetId,
                'target_type' => $targetType,
                'module' => $attachment['module'],
                'seq' => $attachment['seq'],
            ]);
        }
    }

    protected function deleteQuestions($conditions)
    {
        $questionCount = $this->getQuestionDao()->count($conditions);
        $questions = $this->getQuestionDao()->search($conditions, [], 0, $questionCount);

        $result = $this->getQuestionDao()->batchDelete($conditions);
        if (!empty($questions)) {
            $this->getAttachmentService()->batchDeleteAttachment(['target_ids' => ArrayToolkit::column($questions, 'id'), 'target_type' => 'question']);
        }

        return $result;
    }

    public function getQuestionIncludeDeleted($questionId)
    {
        return $this->getQuestionDao()->getIncludeDeleted($questionId);
    }

    public function getQuestion($questionId)
    {
        return $this->getQuestionDao()->get($questionId);
    }

    public function countItemTypesNum($items)
    {
        $typesNum = [
            SingleChoiceItem::TYPE => 0,
            ChoiceItem::TYPE => 0,
            UncertainChoiceItem::TYPE => 0,
            DetermineItem::TYPE => 0,
            FillItem::TYPE => 0,
            EssayItem::TYPE => 0,
            MaterialItem::TYPE => 0,
        ];

        foreach ($items as $item) {
            ++$typesNum[$item['type']];
        }

        return $typesNum;
    }

    public function findDuplicatedMaterialIds($itemBankId, $items)
    {
        $materialHashes = [];
        $materials = [];
        foreach ($items as $item) {
            $item['bank_id'] = $itemBankId;
            $item = $this->getItemProcessor($item['type'])->process($item);
            $materialHashes[] = md5($item['material']);
            $materials[] = $item['material'];
        }

        $selfDuplicatedIdsGroup = [];
        foreach (array_count_values($materials) as $value => $count) {
            if ($count > 1) {
                $selfDuplicatedIdsGroup[] = array_keys($materials, $value);
            }
        }

        $allDuplicatedIds = [];
        foreach ($selfDuplicatedIdsGroup as $selfDuplicatedIds) {
            foreach ($selfDuplicatedIds as $selfDuplicatedId) {
                $allDuplicatedIds[$selfDuplicatedId]['local'] = array_values(array_diff($selfDuplicatedIds, [$selfDuplicatedId]));
            }
        }

        $duplicatedMaterials = array_column($this->getItemDao()->findMaterialByMaterialHashes($itemBankId, $materialHashes), 'material');
        $duplicatedIds = array_keys(array_intersect($materials, $duplicatedMaterials));
        foreach ($duplicatedIds as $duplicatedId) {
            $allDuplicatedIds[$duplicatedId]['remote'] = true;
        }

        return $allDuplicatedIds;
    }

    public function isMaterialDuplicative($itemBankId, $material, $items = [], $itemId = 0)
    {
        $material = $this->purifyHtml(trim($material));
        $material = preg_replace('/\[\[.*?\]\]/', '[[]]', $material);
        $materialHash = md5($material);

        if ($items) {
            foreach ($items as $item) {
                $item['bank_id'] = $itemBankId;
                $item = $this->getItemProcessor($item['type'])->process($item);
                if ($material == $item['material']) {
                    return true;
                }
            }
        }

        $items = $this->getItemDao()->search(['bank_id' => $itemBankId, 'material_hash' => $materialHash], [], 0, PHP_INT_MAX, ['id', 'material']);
        $items = array_filter($items, function ($item) use ($material, $itemId) {
            return $material == $item['material'] && $item['id'] != $itemId;
        });
        if ($items) {
            return true;
        }

        return false;
    }

    public function findDuplicatedMaterials($bankId, $categoryId = 0)
    {
        $categoryIds = [];
        if ('' != $categoryId) {
            $categoryIds = $this->getItemCategoryService()->findCategoryChildrenIds($categoryId);
            $categoryIds[] = $categoryId;
        }
        $duplicatedMaterialHashes = $this->getItemDao()->findDuplicatedMaterialHashes($bankId, $categoryIds);
        if (empty($duplicatedMaterialHashes)) {
            return [];
        }

        return $this->getItemDao()->findDuplicatedMaterials($bankId, $categoryIds, array_column($duplicatedMaterialHashes, 'material_hash'));
    }

    public function findDuplicatedMaterialItems($bankId, $categoryId, $material)
    {
        $material = str_replace('\n', PHP_EOL, $material);
        $conditions = ['bank_id' => $bankId, 'material_hash' => md5($material)];
        if ('' != $categoryId) {
            $categoryIds = $this->getItemCategoryService()->findCategoryChildrenIds($categoryId);
            $categoryIds[] = $categoryId;
            $conditions['category_ids'] = $categoryIds;
        }
        $items = $this->getItemDao()->search($conditions, [], 0, PHP_INT_MAX, ['id', 'material']);
        $items = array_filter($items, function ($item) use ($material) {
            return $item['material'] == $material;
        });

        return array_values($this->findItemsByIds(array_column($items, 'id'), true));
    }

    protected function findQuestionsByItemId($itemId)
    {
        return $this->getQuestionDao()->findByItemId($itemId);
    }

    protected function findQuestionsByItemIds($itemIds)
    {
        return $this->getQuestionDao()->findByItemsIds($itemIds);
    }

    protected function filterItemConditions($conditions)
    {
        if (!empty($conditions['keyword'])) {
            $conditions['material'] = '%'.trim($conditions['keyword']).'%';
            unset($conditions['keyword']);
        }

        return $conditions;
    }

    protected function hasImg($text)
    {
        if (preg_match('/<img (.*?)>/', $text)) {
            return true;
        }

        return false;
    }

    protected function purifyHtml($html)
    {
        return $this->biz['item_bank_html_helper']->purify($html);
    }

    /**
     * @param $imgRootDir
     *
     * @return ExportItemsWrapper
     */
    protected function getExportItemsWrapper($imgRootDir)
    {
        $exportItemsWrapper = $this->biz['export_items_wrapper'];
        $exportItemsWrapper->setImgRootDir($imgRootDir);

        return $exportItemsWrapper;
    }

    /**
     * @param $type
     *
     * @return Item
     */
    protected function getItemProcessor($type)
    {
        return $this->biz['item_type_factory']->create($type);
    }

    /**
     * @return ItemParser
     */
    protected function getItemParser()
    {
        return $this->biz['item_parser'];
    }

    /**
     * @return ItemBankService
     */
    protected function getItemBankService()
    {
        return $this->biz->service('ItemBank:ItemBank:ItemBankService');
    }

    /**
     * @return ItemCategoryService
     */
    protected function getItemCategoryService()
    {
        return $this->biz->service('ItemBank:Item:ItemCategoryService');
    }

    /**
     * @return ItemDao
     */
    protected function getItemDao()
    {
        return $this->biz->dao('ItemBank:Item:ItemDao');
    }

    /**
     * @return QuestionDao
     */
    protected function getQuestionDao()
    {
        return $this->biz->dao('ItemBank:Item:QuestionDao');
    }

    /**
     * @return AttachmentService
     */
    protected function getAttachmentService()
    {
        return $this->biz->service('ItemBank:Item:AttachmentService');
    }
}
