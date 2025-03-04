<?php

namespace AppBundle\Controller\QuestionBank;

use AppBundle\Common\ArrayToolkit;
use AppBundle\Common\Paginator;
use AppBundle\Controller\BaseController;
use Biz\Activity\Service\TestpaperActivityService;
use Biz\Question\Traits\QuestionImportTrait;
use Biz\QuestionBank\QuestionBankException;
use Biz\QuestionBank\Service\QuestionBankService;
use Biz\Testpaper\TestpaperException;
use Codeages\Biz\ItemBank\Assessment\Service\AssessmentService;
use Codeages\Biz\ItemBank\Item\Service\ItemCategoryService;
use Codeages\Biz\ItemBank\Item\Service\ItemService;
use Codeages\Biz\ItemBank\ItemBank\Service\ItemBankService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class TestpaperController extends BaseController
{
    use QuestionImportTrait;

    public function indexAction(Request $request, $id)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            return $this->createMessageResponse('error', '您不是该题库管理者，不能查看此页面！');
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        if (empty($questionBank['itemBank'])) {
            $this->createNewException(QuestionBankException::NOT_FOUND_BANK());
        }

        $conditions = [
            'bank_id' => $questionBank['itemBankId'],
            'displayable' => 1,
        ];

        $paginator = new Paginator(
            $request,
            $this->getAssessmentService()->countAssessments($conditions),
            10
        );

        $assessments = $this->getAssessmentService()->searchAssessments(
            $conditions,
            ['created_time' => 'DESC'],
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        return $this->render('question-bank/testpaper/index.html.twig', [
            'questionBank' => $questionBank,
            'testpapers' => $assessments,
            'users' => $this->getUserService()->findUsersByIds(array_column($assessments, 'updated_user_id')),
            'paginator' => $paginator,
            'testpaperActivities' => $this->getTestpaperActivityService()->findActivitiesByMediaIds(array_column($assessments, 'id')),
        ]);
    }

    public function getTestpaperHtmlAction(Request $request, $id)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            return $this->createMessageResponse('error', '您不是该题库管理者，不能查看此页面！');
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        if (empty($questionBank['itemBank'])) {
            $this->createNewException(QuestionBankException::NOT_FOUND_BANK());
        }

        $conditions = [
            'bank_id' => $questionBank['itemBankId'],
            'nameLike' => $request->query->get('keyword', ''),
            'displayable' => 1,
        ];

        $paginator = new Paginator(
            $this->get('request'),
            $this->getAssessmentService()->countAssessments($conditions),
            10
        );

        $assessments = $this->getAssessmentService()->searchAssessments(
            $conditions,
            ['created_time' => 'DESC'],
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        return $this->render('question-bank/testpaper/testpaper-list-tr.html.twig', [
            'questionBank' => $questionBank,
            'testpapers' => $assessments,
            'users' => $this->getUserService()->findUsersByIds(array_column($assessments, 'updated_user_id')),
            'paginator' => $paginator,
            'testpaperActivities' => $this->getTestpaperActivityService()->findActivitiesByMediaIds(array_column($assessments, 'id')),
            'isSearch' => true,
        ]);
    }

    public function importAction(Request $request, $id)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            return $this->createMessageResponse('error', '您不是该题库管理者，不能查看此页面！');
        }

        return $this->forward('AppBundle:Question/QuestionParser:read', [
            'request' => $request,
            'type' => 'testpaper',
            'questionBank' => $this->getQuestionBankService()->getQuestionBank($id),
        ]);
    }

    public function createAction(Request $request, $id)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            return $this->createMessageResponse('error', '您不是该题库管理者，不能查看此页面！');
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        if (empty($questionBank['itemBank'])) {
            $this->createNewException(QuestionBankException::NOT_FOUND_BANK());
        }

        if ($request->isMethod('POST')) {
            $assessment = $request->request->get('baseInfo', []);
            $sections = $request->request->get('sections', []);
            $assessment['bank_id'] = $questionBank['itemBankId'];
            $assessment['displayable'] = 1;

            if (empty($sections)) {
                return $this->createMessageResponse('error', '试卷模块不能为空！');
            }
            $assessment['sections'] = json_decode($sections, true);

            if ($this->calculateItemCount($assessment['sections']) > 2000) {
                return $this->createMessageResponse('error', '试卷题目数量不能超过2000！');
            }

            $assessment['bank_id'] = $questionBank['itemBankId'];
            $assessment['sections'] = $this->processAssessmentSections($assessment['sections']);
            $this->getAssessmentService()->createAssessment($assessment);

            return $this->createJsonResponse([
                'goto' => $this->generateUrl('question_bank_manage_testpaper_list', ['id' => $id]),
            ]);
        }

        return $this->render('question-bank/testpaper/manage/testpaper-form.html.twig', [
            'questionBank' => $questionBank,
            'showBaseInfo' => '1',
        ]);
    }

    private function processAssessmentSections($sections)
    {
        foreach ($sections as &$section) {
            $items = $section['items'];
            foreach ($items as &$item) {
                $questionsIds = ArrayToolkit::column($item['questions'], 'id');
                $questions = $this->getItemService()->findQuestionsByQuestionIds($questionsIds);
                $questions = ArrayToolkit::index($questions, 'id');
                $itemQuestions = [];
                foreach ($item['questions'] as &$question) {
                    $question['score_rule'] = [
                        'score' => $question['score'],
                        'scoreType' => empty($question['scoreType']) ? 'question' : $question['scoreType'],
                        'otherScore' => empty($question['otherScore']) ? 0 : $question['otherScore'],
                    ];
                    $itemQuestions[] = array_merge(
                        $questions[$question['id']],
                        $question
                    );
                }

                $item['questions'] = $itemQuestions;
            }
            $section['items'] = $items;
        }

        return $sections;
    }

    public function createRandomTestpaperAction(Request $request, $id)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            return $this->createMessageResponse('error', '您不是该题库管理者，不能查看此页面！');
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        if (empty($questionBank['itemBank'])) {
            $this->createNewException(QuestionBankException::NOT_FOUND_BANK());
        }

        if ($request->isMethod('POST')) {
            $fields = $request->request->all();
            $fields['itemBankId'] = $questionBank['itemBankId'];

            $assessment = $this->getBiz()['testpaper_builder.random_testpaper']->build($fields);

            return $this->redirect(
                $this->generateUrl(
                    'question_bank_manage_testpaper_edit',
                    ['id' => $id, 'assessmentId' => $assessment['id'], 'showBaseInfo' => '0']
                )
            );
        }

        $types = $this->getQuestionTypes();

        $conditions = [
            'bank_id' => $questionBank['itemBankId'],
        ];

        $typesNum = $this->getItemService()->getItemCountGroupByTypes($conditions);
        $typesNum = ArrayToolkit::index($typesNum, 'type');

        return $this->render('question-bank/testpaper/random/testpaper-form.html.twig', [
            'categoryTree' => $this->getItemCategoryService()->getItemCategoryTree($questionBank['itemBankId']),
            'types' => $types,
            'typesNum' => $typesNum,
            'questionBank' => $questionBank,
        ]);
    }

    public function editAction(Request $request, $id, $assessmentId)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            return $this->createMessageResponse('error', '您不是该题库管理者，不能查看此页面！');
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);

        $assessment = $this->getAssessmentService()->getAssessment($assessmentId);

        if (!$assessment || $assessment['bank_id'] != $questionBank['itemBankId']) {
            return $this->createMessageResponse('error', 'testpaper not found');
        }

        if ($request->isMethod('POST')) {
            $assessment = $request->request->get('baseInfo', []);
            $sections = $request->request->get('sections', '');

            $sections = json_decode($sections, true);
            if (empty($sections)) {
                return $this->createJsonResponse(['error' => '试卷模块不能为空!']);
            }

            $assessment['sections'] = $sections;

            if ($this->calculateItemCount($assessment['sections']) > 2000) {
                return $this->createJsonResponse(['error' => '试卷题目数量不能超过2000!']);
            }
            $assessment['sections'] = $this->processAssessmentSections($assessment['sections']);
            $this->getAssessmentService()->updateAssessment($assessmentId, $assessment);

            $this->getLogService()->info('question_bank', 'edit_testpaper', "用户{$this->getCurrentUser()->nickname}修改了{$questionBank['name']}名为{$assessment['name']}的试卷");

            return $this->createJsonResponse([
                'goto' => $this->generateUrl('question_bank_manage_testpaper_list', ['id' => $id]),
            ]);
        }

        $assessmentDetail = $this->getAssessmentService()->showAssessment($assessment['id']);
        $itemCategories = $this->getItemCategoryService()->findItemCategoriesByBankId($questionBank['itemBankId']);
        $itemCategories = ArrayToolkit::index($itemCategories, 'id');

        return $this->render('question-bank/testpaper/manage/testpaper-form.html.twig', [
            'questionBank' => $questionBank,
            'testpaper' => $assessment,
            'isImport' => $request->query->get('isImport', 0),
            'sections' => $this->setSectionsTypeAndQuestionCount($assessmentDetail['sections']),
            'itemCategories' => $itemCategories,
            'showBaseInfo' => $request->query->get('showBaseInfo', '1'),
        ]);
    }

    public function deleteTestpapersAction(Request $request, $id)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            throw $this->createAccessDeniedException();
        }

        $ids = $request->request->get('ids');

        $assessments = $this->getAssessmentService()->findAssessmentsByIds($ids);
        if (empty($assessments)) {
            $this->createNewException(TestpaperException::NOTFOUND_TESTPAPER());
        }

        $status = ArrayToolkit::column($assessments, 'status');
        if (in_array('open', $status)) {
            $this->createNewException(TestpaperException::OPEN_TESTPAPER_FORBIDDEN_DELETE());
        }

        foreach ($assessments as $assessment) {
            $this->getAssessmentService()->deleteAssessment($assessment['id']);
        }

        return $this->createJsonResponse(true);
    }

    public function deleteAction(Request $request, $id, $assessmentId)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            throw $this->createAccessDeniedException();
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        $assessment = $this->getAssessmentService()->getAssessment($assessmentId);

        if (empty($assessment) || $assessment['bank_id'] != $questionBank['itemBankId']) {
            return $this->createMessageResponse('error', 'testpaper not found');
        }

        $this->getAssessmentService()->deleteAssessment($assessmentId);

        return $this->createJsonResponse(true);
    }

    public function publishAction(Request $request, $id, $assessmentId)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            throw $this->createAccessDeniedException();
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        $assessment = $this->getAssessmentService()->getAssessment($assessmentId);
        if (empty($assessment) || $assessment['bank_id'] != $questionBank['itemBankId']) {
            $this->createNewException(TestpaperException::NOTFOUND_TESTPAPER());
        }

        $this->getAssessmentService()->openAssessment($assessmentId);

        return $this->createJsonResponse(true);
    }

    public function closeAction(Request $request, $id, $assessmentId)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            throw $this->createAccessDeniedException();
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        $assessment = $this->getAssessmentService()->getAssessment($assessmentId);
        if (empty($assessment) || $assessment['bank_id'] != $questionBank['itemBankId']) {
            $this->createNewException(TestpaperException::NOTFOUND_TESTPAPER());
        }

        $this->getAssessmentService()->closeAssessment($assessmentId);

        return $this->createJsonResponse(true);
    }

    public function previewAction(Request $request, $id, $assessmentId)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            throw $this->createAccessDeniedException();
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        $assessment = $this->getAssessmentService()->showAssessment($assessmentId);
        if (!$assessment || $assessment['bank_id'] != $questionBank['itemBankId']) {
            return $this->createMessageResponse('error', '试卷不存在');
        }

        if ('closed' === $assessment['status']) {
            return $this->createMessageResponse('warning', '试卷已关闭');
        }
        if (empty($assessment['item_count'])) {
            return $this->createMessageResponse('warning', '当前试卷所有题目内容均已被删除');
        }

        return $this->render('testpaper/manage/preview.html.twig', [
            'assessment' => $this->addArrayEmphasisStyle($assessment),
        ]);
    }

    public function exportAction(Request $request, $id, $assessmentId)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            throw $this->createAccessDeniedException();
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        $assessment = $this->getAssessmentService()->getAssessment($assessmentId);

        if (empty($assessment) || $assessment['bank_id'] != $questionBank['itemBankId']) {
            return $this->createMessageResponse('error', '试卷不存在');
        }

        $imgRootDir = $this->get('kernel')->getContainer()->getParameter('kernel.root_dir').'/../web';
        $fileName = rawurlencode("{$assessment['name']}.docx");
        $wordFileName = rawurlencode(substr($assessment['name'], 0, 20).'.docx');
        $baseDir = $this->get('kernel')->getContainer()->getParameter('topxia.disk.local_directory');
        $path = $baseDir.DIRECTORY_SEPARATOR.$wordFileName;

        $result = $this->getAssessmentService()->exportAssessment($assessmentId, $path, $imgRootDir);

        if (empty($result)) {
            return $this->createMessageResponse('info', '导出试卷为空', null, 3000, $this->generateUrl('question_bank_manage_testpaper_list', ['id' => $id]));
        }

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename='.$fileName,
        ];

        return new BinaryFileResponse($path, 200, $headers);
    }

    public function jsonAction(Request $request, $id)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            return $this->createJsonResponse([]);
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        $conditions = [
            'bank_id' => $questionBank['itemBankId'],
            'displayable' => 1,
            'keyword' => $request->query->get('keyword', ''),
        ];
        $totalCount = $this->getAssessmentService()->countAssessments($conditions);
        $conditions['status'] = 'open';
        $openCount = $this->getAssessmentService()->countAssessments($conditions);

        $pagination = new Paginator(
            $request,
            $openCount,
            10
        );

        $testPapers = $this->getAssessmentService()->searchAssessments(
            $conditions,
            ['created_time' => 'DESC'],
            $pagination->getOffsetCount(),
            $pagination->getPerPageCount()
        );

        foreach ($testPapers as &$testPaper) {
            $testPaper = [
                'id' => $testPaper['id'],
                'name' => $testPaper['name'],
                'score' => $testPaper['total_score'],
            ];
        }

        return $this->createJsonResponse([
            'testPapers' => $testPapers,
            'totalCount' => $totalCount,
            'openCount' => $openCount,
        ]);
    }

    public function questionPickAction(Request $request, $id)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            throw $this->createAccessDeniedException();
        }
        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);

        $conditions = $request->query->all();
        $conditions['bank_id'] = $questionBank['itemBankId'];
        if (!empty($conditions['exclude_ids'])) {
            $conditions['exclude_ids'] = explode(',', $conditions['exclude_ids']);
        }

        $paginator = new Paginator(
            $request,
            $this->getItemService()->countItems($conditions),
            10
        );

        $items = $this->getItemService()->searchItems(
            $conditions,
            ['created_time' => 'ASC', 'id' => 'ASC'],
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        $itemCategories = $this->getItemCategoryService()->findItemCategoriesByBankId($questionBank['itemBankId']);
        $itemCategories = ArrayToolkit::index($itemCategories, 'id');

        return $this->render('question-bank/widgets/question-pick-modal.html.twig', [
            'isSelectBank' => $request->request->get('isSelectBank', 0),
            'items' => $items,
            'paginator' => $paginator,
            'questionBank' => $questionBank,
            'categoryTree' => $this->getItemCategoryService()->getItemCategoryTree($questionBank['itemBankId']),
            'itemCategories' => $itemCategories,
            'excludeIds' => empty($conditions['exclude_ids']) ? '' : $conditions['exclude_ids'],
        ]);
    }

    public function questionSearchAction(Request $request, $id)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            throw $this->createAccessDeniedException();
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);

        $conditions = $request->query->all();
        $conditions['bank_id'] = $questionBank['itemBankId'];
        if (!empty($conditions['exclude_ids'])) {
            $conditions['exclude_ids'] = explode(',', $conditions['exclude_ids']);
        }
        $paginator = new Paginator(
            $request,
            $this->getItemService()->countItems($conditions),
            10
        );

        if (!empty($conditions['categoryId'])) {
            $childrenIds = $this->getItemCategoryService()->findCategoryChildrenIds($conditions['categoryId']);
            $childrenIds[] = $conditions['categoryId'];
            $conditions['category_ids'] = $childrenIds;
            unset($conditions['categoryId']);
        }

        $items = $this->getItemService()->searchItems(
            $conditions,
            ['created_time' => 'ASC', 'id' => 'ASC'],
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );

        $itemCategories = $this->getItemCategoryService()->findItemCategoriesByBankId($questionBank['itemBankId']);
        $itemCategories = ArrayToolkit::index($itemCategories, 'id');

        return $this->render('question-bank/widgets/question-pick-body.html.twig', [
            'items' => $items,
            'paginator' => $paginator,
            'questionBank' => $questionBank,
            'itemCategories' => $itemCategories,
        ]);
    }

    public function pickedQuestionAction(Request $request, $id)
    {
        if (!$this->getQuestionBankService()->canManageBank($id)) {
            throw $this->createAccessDeniedException();
        }

        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        $itemCategories = $this->getItemCategoryService()->findItemCategoriesByBankId($questionBank['itemBankId']);
        $itemCategories = ArrayToolkit::index($itemCategories, 'id');

        $typeHtml = [];
        $typeQuestions = $request->request->get('typeQuestions', []);
        foreach ($typeQuestions as $type => $items) {
            if (empty($items)) {
                continue;
            }

            $typeHtml[$type] = $this->renderView('question-bank/widgets/picked-question.html.twig', [
                'items' => $this->getItemService()->findItemsByIds(array_keys($items), true),
                'questionBank' => $questionBank,
                'itemCategories' => $itemCategories,
                'type' => $type,
            ]);
        }

        return $this->createJsonResponse($typeHtml);
    }

    public function buildCheckAction(Request $request, $id, $type)
    {
        $questionBank = $this->getQuestionBankService()->getQuestionBank($id);
        if (empty($questionBank)) {
            throw $this->createAccessDeniedException();
        }

        $data = $request->request->all();
        $data['itemBankId'] = $questionBank['itemBankId'];

        $result = $this->getBiz()['testpaper_builder.random_testpaper']->canBuild($data);

        return $this->createJsonResponse($result);
    }

    protected function calculateItemCount($sections)
    {
        $itemCount = 0;
        foreach ($sections as $section) {
            if (!empty($section['items'])) {
                $itemCount += count($section['items']);
            }
        }

        return $itemCount;
    }

    protected function setSectionsTypeAndQuestionCount($sections)
    {
        foreach ($sections as &$section) {
            // $section['type'] = $section['items'][0]['type'];
            // todo:$section['items'][0]的type不一定存在,可能item刚好被删除了;
            foreach ($section['items'] as $item) {
                if (isset($item['type'])) {
                    $section['type'] = $item['type'];
                    break;
                }
            }

            $itemsCount = $section['question_count'] - count(array_filter(ArrayToolkit::column($section['items'], 'isDelete')));
            $section['question_count'] = $itemsCount < 0 ? 0 : $itemsCount;
        }

        return ArrayToolkit::index($sections, 'type');
    }

    protected function getQuestionTypes()
    {
        $typesConfig = $this->get('extension.manager')->getQuestionTypes();

        $types = [];
        foreach ($typesConfig as $type => $typeConfig) {
            $types[$type] = [
                'name' => $typeConfig['name'],
                'hasMissScore' => $typeConfig['hasMissScore'],
            ];
        }

        return $types;
    }

    /**
     * @return QuestionBankService
     */
    protected function getQuestionBankService()
    {
        return $this->createService('QuestionBank:QuestionBankService');
    }

    /**
     * @return ItemBankService
     */
    protected function getItemBankService()
    {
        return $this->createService('ItemBank:ItemBank:ItemBankService');
    }

    /**
     * @return ItemService
     */
    protected function getItemService()
    {
        return $this->createService('ItemBank:Item:ItemService');
    }

    /**
     * @return ItemCategoryService
     */
    protected function getItemCategoryService()
    {
        return $this->createService('ItemBank:Item:ItemCategoryService');
    }

    /**
     * @return AssessmentService
     */
    protected function getAssessmentService()
    {
        return $this->createService('ItemBank:Assessment:AssessmentService');
    }

    /**
     * @return TestpaperActivityService
     */
    protected function getTestpaperActivityService()
    {
        return $this->createService('Activity:TestpaperActivityService');
    }
}
