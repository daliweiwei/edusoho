<?php

namespace AppBundle\Component\Notification\WeChatTemplateMessage;

class TemplateUtil
{
    public static function templates()
    {
        $templates = array(
            'homeworkOrTestPaperReview' => array(
                'id' => 'OPENTM414077970',
                'name' => '作业/试卷批改提醒',
                'content' => 'wechat.notification.template.homework_or_testpaper_need_review',
                'detail' => '{{first.DATA}}<br>时间：{{keyword1.DATA}}<br>作业数目：{{keyword2.DATA}}<br>{{remark.DATA}}',
                'object' => '课程教师',
                'status' => 0,
            ),
            'courseRemind' => array(
                'id' => 'OPENTM400833477',
                'name' => '上课提醒',
                'content' => 'wechat.notification.template.remind_course',
                'rule' => 'wechat.notification.template.remind_course.rule',
                'detail' => '{{first.DATA}}<br>{{keyword1.DATA}}<br>{{keyword2.DATA}}<br>{{remark.DATA}}',
                'object' => '有未完成的教学计划的学员',
                'status' => 0,
            ),
            'askQuestion' => array(
                'id' => 'OPENTM414529612',
                'name' => '答疑提醒',
                'content' => 'wechat.notification.template.ask_question',
                'rule' => 'wechat.notification.template.ask_question.rule',
                'detail' => '尊敬的老师，您的在教课程中有学员发布了提问<br>{{keyword1.DATA}}<br>{{keyword2.Data}}<br>时间{{keyword3.Data}}<br>{{remark.DATA}}',
                'object' => '课程教师',
                'status' => 0,
            ),
            'answerQuestion' => array(
                'id' => 'OPENTM416215703',
                'name' => '问题回复通知',
                'content' => 'wechat.notification.template.answer_question',
                'rule' => 'wechat.notification.template.answer_question.rule',
                'detail' => '{{first.DATA}}<br>提问时间：{{keyword1.DATA}}<br>回复内容：{{keyword2.DATA}}<br>{{remark.DATA}}',
                'object' => '提问者',
                'status' => 0,
            ),
            'vipExpired' => array(
                'id' => 'OPENTM401520362',
                'name' => '会员到期提醒',
                'content' => 'wechat.notification.template.vip_expired',
                'rule' => 'wechat.notification.template.vip_expired.rule',
                'detail' => '{{first.DATA}}<br>开通时间：{{keyword1.DATA}}<br>到期时间：{{keyword2.DATA}}<br>{{remark.DATA}}',
                'object' => '单个用户',
                'status' => 0,
            ),
            'liveOpen' => array(
                'id' => 'TM00080',
                'name' => '直播开课通知',
                'content' => 'wechat.notification.template.live_start',
                'rule' => 'wechat.notification.template.live_start.rule',
                'detail' => '您好，{{userName.DATA}}。<br>您报名参加的{{courseName.DATA}}将于{{date.DATA}}开课，特此通知。<br>{{remark.DATA}}',
                'object' => '课程学员',
                'status' => 0,
            ),
            'homeworkResult' => array(
                'id' => 'OPENTM400905764',
                'name' => '作业结果通知',
                'content' => 'wechat.notification.template.homework_result',
                'rule' => 'wechat.notification.template.homework_result.rule',
                'detail' => '{{first.DATA}}<br>作业名称：{{keyword1.DATA}}<br>所属课程：{{keyword2.DATA}}<br>辅导老师：{{keyword3.DATA}}<br>{{remark.DATA}}',
                'object' => '作业提交学员',
                'status' => 0,
            ),
            'examResult' => array(
                'id' => 'OPENTM409257668',
                'name' => '考试结果通知',
                'content' => 'wechat.notification.template.exam_result',
                'rule' => 'wechat.notification.template.exam_result.rule',
                'detail' => '{{first.DATA}}<br>考试科目：{{keyword1.DATA}}<br>考试成绩：{{keyword2.DATA}}<br>{{remark.DATA}',
                'object' => '试卷提交学员',
                'status' => 0,
            ),
            'taskUpdate' => array(
                'id' => 'TM408917738',
                'name' => '课程更新提醒',
                'content' => 'wechat.notification.template.lesson_add',
                'rule' => 'wechat.notification.template.lesson_add.rule',
                'detail' => '{{first.DATA}}<br>课程名称：{{keyword1.DATA}}<br>课程类别：{{keyword2.DATA}}<br>课程老师：{{keyword3.DATA}}<br>课程时间：{{keyword4.DATA}}<br>{{remark.DATA}}',
                'object' => '课程学员',
                'status' => 0,
            ),
            'coinRecharge' => array(
                'id' => 'OPENTM401498850',
                'name' => '充值成功通知',
                'content' => 'wechat.notification.template.charge_success',
                'rule' => 'wechat.notification.template.charge_success.rule',
                'detail' => '{{first.DATA}}<br>充值类型：{{keyword1.DATA}}<br>充值订单号：{{keyword2.DATA}}<br>充值金额：{{keyword3.DATA}}<br>充值时间：{{keyword4.DATA}}<br>{{remark.DATA}}',
                'object' => '购买者',
                'status' => 0,
            ),
            'paySuccess' => array(
                'id' => 'OPENTM417184648',
                'name' => '购买成功通知',
                'content' => 'wechat.notification.template.buy_success',
                'rule' => 'wechat.notification.template.buy_success.rule',
                'detail' => '{{first.DATA}}<br>订单详情：{{keyword1.DATA}}<br>订单价格：{{keyword2.DATA}}<br>订单时间：{{keyword3.DATA}}<br>会员到期日：{{keyword4.DATA}}<br>{{remark.DATA}}',
                'object' => '购买者',
                'status' => 0,
            ),
        );

        return $templates;
    }
}
