<?php

namespace Codeages\Biz\ItemBank\Item\Wrapper;

use Biz\CloudFile\Service\CloudFileService;
use Biz\File\Service\UploadFileService;
use Codeages\Biz\Framework\Context\Biz;
use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\ItemBank\Item\Service\AttachmentService;

class AttachmentWrapper
{
    protected $biz;

    public function __construct(Biz $biz)
    {
        $this->biz = $biz;
    }

    public function wrap($item)
    {
        if (empty($item)) {
            return [];
        }

        $item['attachments'] = $this->getAttachmentService()->findAttachmentsByTargetIdAndTargetType($item['id'], 'item');
        if (empty($item['questions'])) {
            return $item;
        }

        $attachments = $this->getAttachmentService()->findAttachmentsByTargetIdsAndTargetType(
            ArrayToolkit::column($item['questions'], 'id'),
            'question'
        );
        $sortAttachments = ArrayToolkit::group($attachments, 'module');
        foreach ($sortAttachments as $sortAttachment) {
            $attachments = ArrayToolkit::sort($attachments, 'seq', SORT_ASC);
        }
        $globalIds = ArrayToolkit::column($attachments, 'global_id');
        $attachments = ArrayToolkit::group($attachments, 'target_id');
        $files = $globalIds ? $this->getUploadFileService()->searchCloudFilesFromLocal([
            'globalIds' => $globalIds,
            'questionBank' => 1,
            'resType' => 'attachment',
        ], [], 0, PHP_INT_MAX) : [];
        $files = ArrayToolkit::index($files, 'globalId');
        foreach ($item['questions'] as &$question) {
            $question['attachments'] = empty($attachments[$question['id']]) ? [] : $attachments[$question['id']];
            foreach ($question['attachments'] as &$attachment) {
                $attachment['length'] = $files[$attachment['global_id']]['length'] ?? 0;
                if ('video' == $attachment['file_type']) {
                    $attachment['thumbnail'] = $files[$attachment['global_id']]['thumbnail'] ?? null;
                }
            }
        }

        return $item;
    }

    /**
     * @return AttachmentService
     */
    protected function getAttachmentService()
    {
        return $this->biz->service('ItemBank:Item:AttachmentService');
    }

    /**
     * @return CloudFileService
     */
    protected function getCloudFileService()
    {
        return $this->biz->service('CloudFile:CloudFileService');
    }

    /**
     * @return UploadFileService
     */
    protected function getUploadFileService()
    {
        return $this->biz->service('File:UploadFileService');
    }
}
