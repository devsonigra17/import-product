<?php

namespace Dev\ImportProduct\Controller\Adminhtml\Import;

class Import extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            /** @var \Magento\Framework\View\Result\Layout $resultLayout */
            $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);

            /** @var $resultBlock \Magento\ImportExport\Block\Adminhtml\Import\Frame\Result */
            $resultBlock = $resultLayout->getLayout()->getBlock('import.frame.result');
            $resultBlock
                ->addAction('show', 'import_validation_container')
                ->addAction('innerHTML', 'import_validation_container_header', __('Status'))
                ->addAction('hide', ['edit_form', 'upload_button', 'messages']);

            $this->importModel->setData($data);
            //Images can be read only from given directory.
            $this->importModel->setData('images_base_directory', $this->imagesDirProvider->getDirectory());
            $errorAggregator = $this->importModel->getErrorAggregator();
            $errorAggregator->initValidationStrategy(
                $this->importModel->getData(Import::FIELD_NAME_VALIDATION_STRATEGY),
                $this->importModel->getData(Import::FIELD_NAME_ALLOWED_ERROR_COUNT)
            );

            try {
                $this->importModel->importSource();
            } catch (\Exception $e) {
                $resultMessageBlock = $resultLayout->getLayout()->getBlock('messages');
                $message = $this->exceptionMessageFactory->createMessage($e);
                $html = $resultMessageBlock->addMessage($message)->toHtml();
                $errorAggregator->addError(
                    \Magento\ImportExport\Model\Import\Entity\AbstractEntity::ERROR_CODE_SYSTEM_EXCEPTION,
                    \Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingError::ERROR_LEVEL_CRITICAL,
                    null,
                    null,
                    null,
                    $html
                );
            }

            if ($this->importModel->getErrorAggregator()->hasToBeTerminated()) {
                $resultBlock->addError(__('Maximum error count has been reached or system error is occurred!'));
                $this->addErrorMessages($resultBlock, $errorAggregator);
            } else {
                $this->importModel->invalidateIndex();

                $noticeHtml = $this->historyModel->getSummary();

                if ($this->historyModel->getErrorFile()) {
                    $noticeHtml .=  '<div class="import-error-wrapper">' . __('Only the first 100 errors are shown. ')
                                    . '<a href="'
                                    . $this->createDownloadUrlImportHistoryFile($this->historyModel->getErrorFile())
                                    . '">' . __('Download full report') . '</a></div>';
                }

                $resultBlock->addNotice(
                    $noticeHtml
                );

                $this->addErrorMessages($resultBlock, $errorAggregator);
                $resultBlock->addSuccess(__('Import successfully done'));
            }

            return $resultLayout;
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('adminhtml/*/index');
        return $resultRedirect;
    }
}