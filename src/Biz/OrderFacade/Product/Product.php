<?php

namespace Biz\OrderFacade\Product;

use AppBundle\Common\MathToolkit;
use AppBundle\Common\StringToolkit;
use AppBundle\Component\Notification\WeChatTemplateMessage\MessageSubscribeTemplateUtil;
use Biz\AppLoggerConstant;
use Biz\ItemBankExercise\Service\MemberOperationRecordService;
use Biz\MemberOperation\Service\MemberOperationService;
use Biz\OrderFacade\Command\Deduct\PickedDeductWrapper;
use Biz\OrderFacade\Currency;
use Biz\Sms\Service\SmsService;
use Biz\Sms\SmsType;
use Biz\System\Service\LogService;
use Biz\WeChat\Service\WeChatService;
use Codeages\Biz\Framework\Context\BizAware;
use Codeages\Biz\Order\Service\OrderService;
use Codeages\Biz\Order\Status\OrderStatusCallback;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class Product extends BizAware implements OrderStatusCallback
{
    /**
     * 如果是商品，则商品规格ID
     *
     * @var int
     */
    public $goodsSpecsId;

    /**
     * 如果是商品，则商品ID
     *
     * @var int
     */
    public $goodsId;

    /**
     * 购买对象ID
     *
     * @var int
     */
    public $targetId;

    /**
     * 购买对象类型
     *
     * @var string
     */
    public $targetType;

    /**
     * 商品名称
     *
     * @var string
     */
    public $title;

    /**
     * 商品原价
     *
     * @var float
     */
    public $originPrice;

    /**
     * 促销价格
     *
     * @var float
     */
    public $promotionPrice = 0;

    /**
     * 可使用的折扣
     *
     * @var array
     */
    public $availableDeducts = [];

    /**
     * 使用到的折扣
     *
     * @var array
     */
    public $pickedDeducts = [];

    /**
     * 返回的链接
     *
     * @var string
     */
    public $backUrl = '';

    /**
     * 成功支付返回链接
     *
     * @var string
     */
    public $successUrl = '';

    /**
     * 最大虚拟币抵扣百分比
     *
     * @var int
     */
    public $maxRate = 100;

    /**
     * 商品数量
     *
     * @var int
     */
    public $num = 1;

    /**
     * 商品单位
     *
     * @var string
     */
    public $unit = '';

    /**
     * 是否可以使用优惠券
     *
     * @var bool
     */
    public $couponEnable = true;

    /**
     * 商品是否可用（如课程、班级被关闭，VIP购买被关闭）
     *
     * @var bool
     */
    public $productEnable = true;

    /**
     * 扩展字段
     */
    private $createExtra;

    /**
     * 封面
     *
     * @var array
     */
    public $cover = [];

    const PRODUCT_VALIDATE_FAIL = '20007';

    abstract public function init(array $params);

    abstract public function validate();

    public function setAvailableDeduct($params = [])
    {
        /** @var $pickedDeductWrapper PickedDeductWrapper */
        $availableDeductWrapper = $this->biz['order.product.available_deduct_wrapper'];

        $availableDeductWrapper->wrapper($this, $params);
    }

    public function setPickedDeduct($params)
    {
        /** @var $pickedDeductWrapper PickedDeductWrapper */
        $pickedDeductWrapper = $this->biz['order.product.picked_deduct_wrapper'];

        $pickedDeductWrapper->wrapper($this, $params);
    }

    public function getPayablePrice()
    {
        $payablePrice = $this->originPrice;
        foreach ($this->pickedDeducts as $deduct) {
            $payablePrice -= $deduct['deduct_amount'];
        }

        return $payablePrice > 0 ? $payablePrice : 0;
    }

    public function getDeducts()
    {
        $deducts = [];
        foreach ($this->pickedDeducts as $deduct) {
            $deducts[$deduct['deduct_type']] = $deduct['deduct_amount'];
        }

        return $deducts;
    }

    public function getMaxCoinAmount()
    {
        return round(($this->maxRate / 100) * $this->getCurrency()->convertToCoin($this->originPrice), 2);
    }

    protected function smsCallback($orderItem, $targetName)
    {
        try {
            $smsType = 'sms_'.$this->targetType.'_buy_notify';
            $userId = $orderItem['user_id'];
            $parameters = [];
            $parameters['order_title'] = '购买'.$targetName.'-'.$orderItem['title'];
            $parameters['order_title'] = StringToolkit::cutter($parameters['order_title'], 20, 15, 4);
            $price = MathToolkit::simple($orderItem['order']['pay_amount'], 0.01);
            $parameters['totalPrice'] = $price.'元';

            if ($this->getSmsService()->isOpen($smsType)) {
                return $this->getSmsService()->smsSend($smsType, [$userId], SmsType::BUY_NOTIFY, $parameters);
            }

            if ($this->getWeChatService()->isSubscribeSmsEnabled(MessageSubscribeTemplateUtil::TEMPLATE_PAY_SUCCESS)) {
                return $this->getWeChatService()->sendSubscribeSms(
                    MessageSubscribeTemplateUtil::TEMPLATE_PAY_SUCCESS,
                    [$userId],
                    SmsType::BUY_NOTIFY,
                    $parameters
                );
            }
        } catch (\Exception $e) {
            $this->getLogService()->error(AppLoggerConstant::SMS, 'sms_'.$this->targetType.'_buy_notify', "发送短信通知失败:userId:{$orderItem['user_id']}, targetType:{$this->targetType}, targetId:{$this->targetId}", ['error' => $e->getMessage()]);
        }
    }

    protected function updateMemberRecordByRefundItem($orderItem)
    {
        $orderRefund = $this->getOrderRefundService()->getOrderRefundById($orderItem['refund_id']);
        $record = [
            'reason' => $orderRefund['reason'],
            'refund_id' => $orderRefund['id'],
            'reason_type' => 'refund',
        ];
        if ($orderItem['target_type'] == 'item_bank_exercise'){
            $record['refundId'] = $orderRefund['id'];
            $this->getItemBankExerciseMemberOperationRecord()->updateRefundInfoByOrderId($orderRefund['order_id'], $record);
        }else{
            $this->getMemberOperationService()->updateRefundInfoByOrderId($orderRefund['order_id'], $record);
        }
    }

    public function getCreateExtra()
    {
        return empty($this->createExtra) ? [] : $this->createExtra;
    }

    public function setCreateExtra($createExtra)
    {
        $this->createExtra = empty($this->createExtra) ? $createExtra : array_merge($this->createExtra, $createExtra);
    }

    public function getSnapShot()
    {
        return [];
    }

    protected function generateUrl($route, $parameters, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        global $kernel;
        $router = $this->decorateRouter($kernel->getContainer()->get('router'));

        return $router->generate($route, $parameters, $referenceType);
    }

    protected function decorateRouter($router)
    {
        $routerContext = $router->getContext();
        if ('localhost' == $routerContext->getHost()) {
            $url = $this->getSettingService()->node('site.url');
            if (!empty($url)) {
                $parsedUrl = parse_url($url);

                empty($parsedUrl['host']) ?: $routerContext->setHost($parsedUrl['host']);
                empty($parsedUrl['scheme']) ?: $routerContext->setScheme($parsedUrl['scheme']);
            }
        }

        return $router;
    }

    /**
     * @return Currency
     */
    protected function getCurrency()
    {
        return $this->biz['currency'];
    }

    /**
     * @return SmsService
     */
    private function getSmsService()
    {
        return $this->biz->service('Sms:SmsService');
    }

    /**
     * @return LogService
     */
    protected function getLogService()
    {
        return $this->biz->service('System:LogService');
    }

    /**
     * @return OrderService
     */
    protected function getOrderService()
    {
        return $this->biz->service('Order:OrderService');
    }

    /**
     * @return OrderRefundService
     */
    protected function getOrderRefundService()
    {
        return $this->biz->service('OrderFacade:OrderRefundService');
    }

    /**
     * @return MemberOperationService
     */
    protected function getMemberOperationService()
    {
        return $this->biz->service('MemberOperation:MemberOperationService');
    }

    /**
     * @return WeChatService
     */
    protected function getWeChatService()
    {
        return $this->biz->service('WeChat:WeChatService');
    }

    /**
     * @return MemberOperationRecordService
     */
    protected function getItemBankExerciseMemberOperationRecord()
    {
        return $this->biz->service('ItemBankExercise:MemberOperationRecordService');
    }
}
