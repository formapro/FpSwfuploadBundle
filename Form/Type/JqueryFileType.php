<?php
namespace Fp\SwfuploadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;

class JqueryFileType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $jqueryFileOptions = array();
        foreach($options as $name => $value) {
            if(0 === strpos($name, 'jquery_file_')) {
                $jqueryFileOptions[str_replace('jquery_file_', '', $name)] = $value;
            }
        }

        $jqueryFileOptions['paramName'] = isset($jqueryFileOptions['paramName']) ?
            $jqueryFileOptions['paramName'] :
            $view->vars['full_name']
        ;

        $jqueryFileOptions = array_filter($jqueryFileOptions, function($value) {
            return !is_null($value);
        });

        $view->vars['jquery_file_options'] = $jqueryFileOptions;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'jquery_file_url',
        ));

        $resolver->setDefaults(array(
            'jquery_file_inputSelector' => 'input:file',
            'jquery_file_type' => null,
            'jquery_file_dataType' => null,
            'jquery_file_dropZone' => null,
            'jquery_file_pasteZone' => null,
            'jquery_file_fileInput' => null,
            'jquery_file_replaceFileInput' => null,
            'jquery_file_paramName' => null,
            'jquery_file_formAcceptCharset' => null,
            'jquery_file_singleFileUploads' => null,
            'jquery_file_limitMultiFileUploads' => null,
            'jquery_file_limitMultiFileUploadSize' => null,
            'jquery_file_limitMultiFileUploadSizeOverhead' => null,
            'jquery_file_sequentialUploads' => null,
            'jquery_file_limitConcurrentUploads' => null,
            'jquery_file_forceIframeTransport' => null,
            'jquery_file_initialIframeSrc' => null,
            'jquery_file_redirect' => null,
            'jquery_file_redirectParamName' => null,
            'jquery_file_postMessage' => null,
            'jquery_file_multipart' => null,
            'jquery_file_maxChunkSize' => null,
            'jquery_file_uploadedBytes' => null,
            'jquery_file_recalculateProgress' => null,
            'jquery_file_progressInterval' => null,
            'jquery_file_bitrateInterval' => null,
            'jquery_file_formData' => null,
        ));

    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'jquery_file';
    }
}
