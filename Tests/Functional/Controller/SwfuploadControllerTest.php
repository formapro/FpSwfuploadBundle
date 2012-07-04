<?php
namespace Fp\SwfuploadBundle\Tests\Integration\Controller;

use Symfony\Component\Form\FormError;
use Fp\SwfuploadBundle\Controller\SwfuploadController;
use Rj\CoreBundle\Test\Phpunit\WebTestCase;

class SwfuploadControllerTest extends WebTestCase
{
    protected $controller;

    public function setUp()
    {
        parent::setUp();

        $this->controller = new SwfuploadController();
        $this->controller->setContainer($this->container);
    }

    /**
     * @test
     */
    public function shouldRenderUploadCodeToExpectedPlaceholder()
    {
        
        $response = $this->controller->getFormAction(array(
            'swfupload_placeholder_container' => 'some-palce-holder',
            'swfupload_button_placeholder_selector' => '.swfupload-placeholder',
            'swfupload_upload_url' => 'http://some_url',
            'swfupload_file_post_name' => 'file'
        ));

        $this->assertInternalType('array', $response);

        $this->assertContains(
            '$("the_placeholder_selector").swfupload({',
            $response->getContent()
        );
    }

    /**
     * @test
     */
    /*
    public function shouldRenderUploadWithExpectedUploadUrl()
    {
        $this->markTestIncomplete('The image upload should be reworked to use API');
        
        $response = $this->controller->uploadCodeAction('a_placeholder_selector', 'the_upload_url');

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertContains(
            'upload_url:  "the_upload_url"',
            $response->getContent()
        );
    }
    */

    /**
     * //@test
     */
    public function shouldRenderSuccessResponseWithImageUrl()
    {
        $this->markTestIncomplete('The image upload should be reworked to use API');
        
        $image = new Image();
        $image->setFile(new File('the_image', $checkPath = false));

        $response = $this->controller->successUploadAction($image);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertContains(
            '{"success":true,"imageId":null,"imageUrl":"the_image"}',
            $response->getContent()
        );
    }

    /**
     * //@test
     */
    public function shouldRenderSuccessUploadWithCustomImageUrl()
    {
        $this->markTestIncomplete('The image upload should be reworked to use API');
        
        $image = new Image();
        $image->setFile(new File('the_image', $checkPath = false));

        $response = $this->controller->successUploadAction($image, $customImageUrl = 'the_custom_image_url');

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertContains(
            '{"success":true,"imageId":null,"imageUrl":"the_custom_image_url"}',
            $response->getContent()
        );
    }

    /**
     * //@test
     */
    public function shouldRenderFailedResponseWithForm()
    {
        $this->markTestIncomplete('The image upload should be reworked to use API');
        
        $form = $this->container->get('form.factory')->create('form');
        $form->addError(new FormError('something go wrong'));

        $response = $this->controller->failedUploadAction($form);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertContains(
            '{"errors":["something go wrong"]}',
            $response->getContent()
        );
    }
}