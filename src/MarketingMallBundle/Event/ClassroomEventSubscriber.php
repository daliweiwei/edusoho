<?php

namespace MarketingMallBundle\Event;

use Codeages\Biz\Framework\Event\Event;
use MarketingMallBundle\Biz\Mall\Service\MallService;
use MarketingMallBundle\Biz\ProductMallGoodsRelation\Service\ProductMallGoodsRelationService;
use MarketingMallBundle\Common\GoodsContentBuilder\ClassroomInfoBuilder;

class ClassroomEventSubscriber extends BaseEventSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            'classroom.course.create' => 'onClassroomCourseCreate',
            'classroom.course.delete' => 'onClassroomCourseDelete',
            'classroom.courses.delete' => 'onClassroomCourseDelete',
            'classroom.course.update' => 'onClassroomCourseUpdate',
            'classroom.info.update' => 'onClassroomInfoUpdate',
            'classroom.delete' => 'onClassroomProductDelete'
        ];
    }

    public function onClassroomCourseCreate(Event $event)
    {
        $classroom = $event->getSubject();
        $newCourseIds = $event->getArgument('newCourseIds');
        if ($newCourseIds) {
            $this->syncClassroomToMarketingMall($classroom['id']);
        }
    }

    public function onClassroomCourseDelete(Event $event)
    {
        if (!$this->getMallService()->isInit()) {
            return;
        }

        $classroom = $event->getSubject();
        $this->syncClassroomToMarketingMall($classroom['id']);
    }

    public function onClassroomCourseUpdate(Event $event)
    {
        $classroom = $event->getSubject();
        $courseIds = $event->getArgument('courseIds');
        $existCourseIds = $event->getArgument('existCourseIds');
        if ($courseIds != $existCourseIds) {
            $this->syncClassroomToMarketingMall($classroom['id']);
        }
    }

    public function onClassroomInfoUpdate(Event $event)
    {
        $id = $event->getSubject()['id'];
        $this->syncClassroomToMarketingMall($id);
    }

    public function onClassroomProductDelete(Event $event)
    {
        $classroom = $event->getSubject();
        $this->deleteClassroomProductToMarketingMall($classroom['id']);
    }

    protected function syncClassroomToMarketingMall($classroomId)
    {

        $this->updateGoodsContent('classroom', new ClassroomInfoBuilder(), $classroomId);
    }

    protected function deleteClassroomProductToMarketingMall($classroomId)
    {
        $relation = $this->getProductMallGoodsRelationService()->getProductMallGoodsRelationByProductTypeAndProductId('classroom', $classroomId);
        if ($relation) {
            $this->getProductMallGoodsRelationService()->deleteProductMallGoodsRelation($relation['id']);

            $this->deleteMallGoods($relation['goodsCode']);
        }
    }

    /**
     * @return ProductMallGoodsRelationService
     */
    protected function getProductMallGoodsRelationService()
    {
        return $this->getBiz()->service('MarketingMallBundle:ProductMallGoodsRelation:ProductMallGoodsRelationService');
    }

    /**
     * @return MallService
     */
    protected function getMallService()
    {
        return $this->getBiz()->service('Mall:MallService');
    }
}
