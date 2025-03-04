<?php

namespace Codeages\Biz\ItemBank\Item\Service\Impl;

use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\ItemBank\BaseService;
use Codeages\Biz\ItemBank\ErrorCode;
use Codeages\Biz\ItemBank\Item\Service\ItemService;
use Codeages\Biz\ItemBank\ItemBank\Service\ItemBankService;
use Codeages\Biz\ItemBank\ItemBank\Exception\ItemBankException;
use Codeages\Biz\ItemBank\Item\Service\ItemCategoryService;
use Codeages\Biz\ItemBank\Item\Dao\ItemCategoryDao;
use Codeages\Biz\ItemBank\Item\Exception\ItemCategoryException;

class ItemCategoryServiceImpl extends BaseService implements ItemCategoryService
{
    public function createItemCategory($category)
    {
        $category = $this->getValidator()->validate($category, [
            'name' => ['required'],
            'parent_id' => ['integer'],
            'bank_id' => ['integer'],
        ]);

        if (empty($this->getItemBankService()->getItemBank($category['bank_id']))) {
            throw new ItemBankException('The item bank is not exist.', ErrorCode::ITEM_BANK_NOT_FOUND);
        }

        $category['created_user_id'] = $category['updated_user_id'] = empty($this->biz['user']['id']) ? 0 : $this->biz['user']['id'];

        return  $this->getItemCategoryDao()->create($category);
    }

    public function createItemCategories($bankId, $parentId, $names)
    {
        if (empty($names)) {
            return [];
        }

        if (empty($this->getItemBankService()->getItemBank($bankId))) {
            throw new ItemBankException('The item bank is not exist.', ErrorCode::ITEM_BANK_NOT_FOUND);
        }

        $categories = [];
        foreach ($names as $name) {
            $categories[] = [
                'bank_id' => $bankId,
                'name' => $name,
                'parent_id' => $parentId,
                'created_user_id' => empty($this->biz['user']['id']) ? 0 : $this->biz['user']['id'],
                'updated_user_id' => empty($this->biz['user']['id']) ? 0 : $this->biz['user']['id'],
            ];
        }

        $categories = $this->getItemCategoryDao()->batchCreate($categories);
        $this->dispatch('itemCategory.create', $categories, ['bankId' => $bankId]);

        return $categories;
    }

    public function updateItemCategory($id, $category)
    {
        $originCategory = $this->getItemCategory($id);
        if (empty($originCategory)) {
            throw new ItemCategoryException('The item bank is not exist.', ErrorCode::ITEM_CATEGORY_NOT_FOUND);
        }

        $category = $this->getValidator()->validate($category, [
            'name' => ['required'],
        ]);

        $category['updated_user_id'] = empty($this->biz['user']['id']) ? 0 : $this->biz['user']['id'];

        $updateCategory = $this->getItemCategoryDao()->update($id, $category);
        $this->dispatch('itemCategory.update', $originCategory, ['fields' => $category]);

        return $updateCategory;
    }

    public function getItemCategory($id)
    {
        return $this->getItemCategoryDao()->get($id);
    }

    public function deleteItemCategory($id)
    {
        $category = $this->getItemCategory($id);
        if (empty($category)) {
            throw new ItemCategoryException('The item category is not exist.', ErrorCode::ITEM_CATEGORY_NOT_FOUND);
        }

        try {
            $this->beginTransaction();
            $ids = $this->findCategoryChildrenIds($id);
            $ids[] = $id;
            $this->getItemCategoryDao()->batchDelete(['ids' => $ids]);

            $items = $this->getItemService()->findItemsByCategoryIds($ids);
            if (!empty($items)) {
                $this->getItemService()->updateItemsCategoryId(array_column($items, 'id'), 0);
            }
            $this->dispatch('itemCategory.delete', $category);
            $this->commit();
        } catch (\Exception $exception) {
            $this->rollback();
            throw $exception;
        }
    }

    public function findItemCategoriesByIds($ids)
    {
        return ArrayToolkit::index($this->getItemCategoryDao()->findByIds($ids), 'id');
    }

    public function findItemCategoriesByBankId($bankId)
    {
        if (empty($this->getItemBankService()->getItemBank($bankId))) {
            throw new ItemBankException('Item bank is not exist.', ErrorCode::ITEM_BANK_NOT_FOUND);
        }

        return $this->getItemCategoryDao()->findByBankId($bankId);
    }

    public function getItemCategoryTree($bankId)
    {
        if (empty($this->getItemBankService()->getItemBank($bankId))) {
            throw new ItemBankException('', ErrorCode::ITEM_BANK_NOT_FOUND);
        }

        $categories = $this->findItemCategoriesByBankId($bankId);

        return $this->buildCategoryTree($categories);
    }

    public function getItemCategoryTreeList($bankId)
    {
        if (empty($this->getItemBankService()->getItemBank($bankId))) {
            throw new ItemBankException('', ErrorCode::ITEM_BANK_NOT_FOUND);
        }

        $categories = $this->findItemCategoriesByBankId($bankId);

        return $this->buildCategoryTreeList($categories, 0);
    }

    protected function prepareCategoryTreeList(&$tree, &$categories, $parentId)
    {
        static $depth = 0;

        if (isset($categories[$parentId]) && is_array($categories[$parentId])) {
            foreach ($categories[$parentId] as $category) {
                ++$depth;
                $category['depth'] = $depth;
                $category['selectable'] = true;
                $tree[] = $category;
                $this->prepareCategoryTreeList($tree, $categories, $category['id']);
                --$depth;
            }
        }

        return $tree;
    }

    public function findCategoryChildrenIds($id)
    {
        $category = $this->getItemCategory($id);

        if (empty($category)) {
            return [];
        }

        list($map) = $this->prepareCategoryTree($this->findItemCategoriesByBankId($category['bank_id']));

        return $this->seekCategoryChildrenIds($map, [$id]);
    }

    public function findMultiCategoryChildrenIds($bankId, $ids)
    {
        list($map) = $this->prepareCategoryTree($this->findItemCategoriesByBankId($bankId));

        return $this->seekCategoryChildrenIds($map, $ids);
    }

    protected function seekCategoryChildrenIds($map, $ids)
    {
        $childrenIds = [];

        foreach ($ids as $id) {
            $childrenIdsQueue = array_column($map[$id]['children'], 'id');
            while (!empty($childrenIdsQueue)) {
                $parent = $map[$childrenIdsQueue[0]];
                $childrenIds[] = array_shift($childrenIdsQueue);
                $childrenIdsQueue = array_merge($childrenIdsQueue, array_column($parent['children'], 'id'));
                unset($parent);
            }
        }

        return $childrenIds;
    }

    protected function prepareCategoryTree($categories)
    {
        $tree = [];
        $map = [];
        foreach ($categories as &$category) {
            $map[$category['id']] = &$category;
            $category['children'] = [];
        }
        foreach ($categories as &$category) {
            if (empty($map[$category['parent_id']])) {
                $tree[] = &$category;
                continue;
            }

            $parent = &$map[$category['parent_id']];
            $parent['children'][] = &$category;
        }
        $getDepth = function ($parentId) use (&$map, &$getDepth) {
            if (empty($map[$parentId])) {
                return 1;
            }
            $parent = &$map[$parentId];

            return 1 + $getDepth($parent['parent_id']);
        };
        foreach ($map as $categoryId => &$category) {
            $category['depth'] = $getDepth($category['parent_id']);
            unset($category);
        }

        return [$map, $tree];
    }

    public function updateItemNumAndQuestionNum($id)
    {
        $itemNum = $this->getItemService()->countItems(['category_id' => $id]);
        $questionNum = $this->getItemService()->countQuestionsByCategoryId($id);

        return $this->getItemCategoryDao()->update($id, ['item_num' => $itemNum, 'question_num' => $questionNum]);
    }

    public function buildItemNumAndQuestionNumBybankId($bankId)
    {
        $items = $this->getItemService()->searchItems(['bank_id' => $bankId], [], 0, PHP_INT_MAX, ['category_id', 'question_num']);
        if (empty($items)) {
            return true;
        }
        
        $updateCategories = [];
        $itemGroups = ArrayToolkit::group($items, 'category_id');
        foreach ($itemGroups as $categoryId => $itemGroup) {
            $updateCategories[] = [
                'id' => $categoryId,
                'question_num' => array_sum(ArrayToolkit::column($itemGroup, 'question_num')),
                'item_num' => count($itemGroup),
            ];
        }

        $this->getItemCategoryDao()->resetItemNumAndQuestionNumByBankId($bankId);
        $this->getItemCategoryDao()->batchUpdate(ArrayToolkit::column($updateCategories, 'id'), $updateCategories);
    }

    public function sortItemCategories($ids)
    {
        $updateFields = [];
        foreach ($ids as $index => $id) {
            $updateFields[] = [
                'seq' => $index + 1
            ];
        }

        return $this->getItemCategoryDao()->batchUpdate($ids, $updateFields);
    }

    public function buildCategoryTreeList($categories, $parentId)
    {
        $categories = ArrayToolkit::group($categories, 'parent_id');
        $tree = [];
        $this->prepareCategoryTreeList($tree, $categories, $parentId);

        return $tree;
    }

    public function buildCategoryTree($categories)
    {
        list($map, $tree) = $this->prepareCategoryTree($categories);

        return $tree;
    }

    /**
     * @return ItemBankService
     */
    protected function getItemBankService()
    {
        return $this->biz->service('ItemBank:ItemBank:ItemBankService');
    }

    /**
     * @return ItemService
     */
    protected function getItemService()
    {
        return $this->biz->service('ItemBank:Item:ItemService');
    }

    /**
     * @return ItemCategoryDao
     */
    protected function getItemCategoryDao()
    {
        return $this->biz->dao('ItemBank:Item:ItemCategoryDao');
    }
}
