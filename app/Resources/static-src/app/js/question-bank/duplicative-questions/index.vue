<template>
  <div id="duplicate-check">
    <div class="duplicate-head flex items-center">
      <span class="duplicate-back flex items-center" @click="goBack">
        <span class="es-icon es-icon-fanhui mr4"></span>
        {{ 'importer.import_back_btn'|trans }}
      </span>
      <span class="duplicate-divider"></span>
      <span class="duplicate-title flex items-center"
        ><span class="bankName flex items-center"
          >【<span class="msg">{{ categoryName }}</span
          >】</span
        >{{ 'question.bank.check'|trans }}</span
      >
    </div>
    <div class="duplicate-body">
      <div class="duplicate-question">
        <div class="duplicate-question-head">
          {{ 'question.bank.common.repeated'|trans }}:<label class="duplicate-question-count">{{
            questionData.length
          }}</label
          >{{ 'subject.question_unit'|trans }}

          <div
            v-show="isShowGuide"
            class="duplicate-question-item duplicate-question-active"
          >
            <div class="duplicate-question-title" v-if="questionData.length">
              {{ questionData[0].displayMaterial | stripTags }}
            </div>
            <span class="duplicate-question-check-count" v-if="questionData.length"
              >{{ questionData[0].frequency }}{{ 'question.bank.unit'|trans }}</span
            >
          </div>

        </div>
        <duplicate-question-item
          v-for="(item, index) in questionData"
          :active="index == activeKey"
          :id="index"
          :key="index"
          :question="item"
          @changeOption="changeOption"
        />
      </div>
      <div class="duplicate-content">
        <div class="duplicate-content-title">{{ 'question.bank.topic.comparison'|trans }}</div>
        <div v-if="questionContentList.length>1" class="mt16 flex flex-nowrap" style="flex: 1;">
          <duplicate-question-content
            v-if="questionContentList[oneIndex]"
            @changeOption="changeOption"
            @getData="getData"
            @changeQuestion="changeQuestion"
            @changeQuestionContent="changeQuestionContent"
            type="one"
            :activeIndex="oneIndex"
            :activeKey="activeKey"
            :nextIndex="twoIndex"
            :questionContent="questionContentList[oneIndex]"
            :count="questionContentList.length"
            :title="questionData[activeKey].material"
            class="mr16"
          />
          <duplicate-question-content
            v-if="questionContentList[twoIndex]"
            @changeOption="changeOption"
            @getData="getData"
            @changeQuestion="changeQuestion"
            @changeQuestionContent="changeQuestionContent"
            type="two"
            :activeIndex="twoIndex"
            :activeKey="activeKey"
            :nextIndex="oneIndex"
            :questionContent="questionContentList[twoIndex]"
            :count="questionContentList.length"
            :title="questionData[activeKey].material"
          />
        </div>
        <div v-else class="no-data text-center">
          <img
            class="no-data-img"
            src="/static-dist/app/img/question-bank/noduplicative.png"
          />
          <div class="no-data-content">{{ 'question.bank.check.result.noData.title'|trans }}</div>
          <button class="return-btn" @click="goBack">{{ 'question.bank.check.result.noData.btn'|trans }}</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import DuplicateQuestionItem from "./components/DuplicateQuestionItem.vue";
import DuplicateQuestionContent from "./components/DuplicateQuestionContent.vue";
import "store";
import { Repeat } from "common/vue/service";

export default {
  data() {
    return {
      activeKey: 0,
      introOption: {
        prevLabel: Translator.trans("course_set.manage.prev_label"),
        nextLabel: Translator.trans("question.bank.next_label"),
        skipLabel: Translator.trans("question.bank.skip_label"),
        doneLabel: Translator.trans("question.bank.finish_label"),
        showBullets: false,
        showStepNumbers: false,
        exitOnEsc: false,
        exitOnOverlayClick: false,
        tooltipClass: "duplicate-intro",
        steps: [],
      },
      oneIndex: 0,
      twoIndex: 1,
      isShowGuide: false,
      questionData: [
        {
          material: "",
          frequency: "",
          displayMaterial: "",
        },
      ],
      questionContentList: [
        {
          analysis: "",
          category_name: "",
          type: "",
        },
      ],
    };
  },
  components: {
    DuplicateQuestionItem,
    DuplicateQuestionContent,
  },
  watch: {
    async questionContentList() {
      if (this.questionContentList.length == 0) {
        return 
      }

      if (this.questionContentList.length > 1) {
        return;
      }

      if (this.activeKey == this.questionData.length-1) {
        this.activeKey -= 1;
      }

      await this.getData();
      await this.changeOption(this.activeKey);
    },
    activeKey() {
      this.changeOption(this.activeKey);
      this.oneIndex = 0;
      this.twoIndex = 1;
    },
  },
  computed: {
    categoryName() {
      if ($("[name=categoryName]").val()) {
        return $("[name=categoryName]").val();
      }

      if ($("[name=categoryId]").val() === "") {
        return Translator.trans("question.bank.all_question");
      }

      return Translator.trans("question.bank.no_category");
    },
  },
  async mounted() {
    await this.getData();

    if(this.questionData.length) {
      await this.changeOption();
    }

    if (!store.get("QUESTION_DUPLICATE_INTRO")) {
      this.isShowGuide = true;

      this.$nextTick(() => {
        this.initGuide();
      });
    }

    if($("[name=page_type]").val() && store.get("DUPLICATE_MATERIAL")) {
      this.matchActive(store.get("DUPLICATE_MATERIAL"))
    }
  },
  methods: {
    initGuide() {
      let that = this;
      that.isShowGuide = true;
      that.introOption.steps = [
        {
          element: ".duplicate-question-head",
          intro: Translator.trans("question.bank.check.guide.one"),
          position: "bottom",
        },
        {
          element: ".question-num",
          intro: Translator.trans("question.bank.check.guide.two"),
          position: "bottom",
        },
      ]
      introJs()
        .setOptions(that.introOption)
        .start()
        .onchange(function () {
          that.isShowGuide = false;
          document.querySelectorAll(".introjs-skipbutton")[0].style.display =
            "inline-block";
          document.querySelectorAll(".introjs-prevbutton")[0].style.display =
            "none";
        })
        .oncomplete(function () {
          store.set("QUESTION_DUPLICATE_INTRO", true);
        });
    },
    changeQuestion(type, index) {
      this[`${type}Index`] = index;
    },
    showChangeOptionErr() {
      this.$error({
        title: Translator.trans("question.bank.error.tip.title"),
        content: Translator.trans("question.bank.error.tip.content"),
        okText: Translator.trans("site.btn.confirm"),
        onOk: async() => {
            await this.getData()
            await this.changeOption()
        }
      });
    },
    async changeOption(activeKey = 0) {
      this.activeKey = activeKey;
      try {
        if(!this.questionData.length) {
          return 
        }

        const res = await Repeat.getRepeatQuestionInfo(
          $("[name=questionBankId]").val(),
          {
            material: this.questionData[activeKey]?.material,
            categoryId: $("[name=categoryId]").val()
          })

        if(!res.length) {
            this.showChangeOptionErr()
            return
        }

        this.questionContentList = res;

        if(this.questionData[activeKey]) {
          this.questionData[activeKey].frequency = res.length.toString();
        }
      } catch (err) {
        this.$message.error(err.message);
      }
    },
    goBack() {
      window.location.href = `/question_bank/${$("[name=questionBankId]").val()}/questions`;
    },
    async getData() {
      try {
        const res = await Repeat.getRepeatQuestion($("[name=questionBankId]").val(), $("[name=categoryId]").val(),)
        this.questionData = res;

        if (!res.length) {
          this.questionContentList = [];
        }
      } catch (err) {
        this.$message.error(err.message);
      }
    },
    matchActive(title) {
      for(let i = 0; i < this.questionData.length; i++) {
        if(this.questionData[i].material == title) {
          this.activeKey = i;
          break;
        }
      }
    },
    changeQuestionContent(index, content) {
      this.questionContentList.splice(this[index],1,content)
    }
  },
};
</script>