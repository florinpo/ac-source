<?php

/**
 * This is the model class for Changing FrontendUser Profile.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.FrontendUser
 *
 */
class ProductSaleCommentForm extends CFormModel {

    public $comment;
    public $product_id;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {

        $purifier = new CHtmlPurifier();
        $purifier->options = array('HTML.Allowed' => '');
        
        return array(
            array('comment, product_id', 'required'),
            array('comment', 'length', 'min' => 3, 'max' => 500),
            array('comment', 'filter', 'filter' => array($purifier, 'purify')),
            array('product_id', 'numerical', 'integerOnly' => true)
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'comment' => t('cms', 'Recensione'),
            'rating' => t('cms', 'Voto')
        );
    }
}