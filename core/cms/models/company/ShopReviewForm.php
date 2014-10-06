<?php

/**
 * This is the model class for Changing FrontendUser Profile.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.FrontendUser
 *
 */
class ShopReviewForm extends CFormModel {

    public $comment;
    public $shop_id;
    public $rating;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {

        $purifier = new CHtmlPurifier();
        $purifier->options = array('HTML.Allowed' => '',);


        return array(
            array('comment, shop_id', 'required'),
            array('comment', 'length', 'min' => 100, 'max' => 800),
            array('rating', 'checkRating'),
            array('comment', 'filter', 'filter' => array($purifier, 'purify')),
            array('shop_id', 'numerical', 'integerOnly' => true)
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
    public function checkRating ($attribute, $params) {
        if(!empty($this->comment)) {
            //$data = explode(',', $this->$attribute);
            if (empty($this->$attribute)) {
                // Checks for only when there is a value
                // @todo Add additional error checking here
                $this->addError($attribute,  t('cms', 'Ti sei dimenticato di dare il tuo voto'));
            }
        }
        
    }
    
}