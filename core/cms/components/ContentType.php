<?php

class ContentType {

    public static function getTypes() {
        return array(
            'product' => array(
                'name' => Yii::t('Global', 'Prodotti vendita'),
                'catmodel' => 'ProductSaleCategoryList',
                'sphinxIndex' => 'products_sale',
                'slug' => 'cerca-prodotti',
                'itemmodel' => array(
                    'name' => 'ProductSale',
                    'pk' => 'id',
                    'key_time' =>'created_time',
                    'sort_attributes'=>array('name'),
                ),
                'jointabel' => array(
                    'tname' => '{{product_sale_category}}',
                    'key_c' => 'categoryId',
                    'key_i' => 'productId'
                )
            ),
            
            'company' => array(
                'name' => Yii::t('Global', 'Aziende'),
                'catmodel' => 'CompanyCats',
                'sphinxIndex' => 'companies',
                'slug' => 'cerca-aziende',
                'itemmodel' => array(
                    'name' => 'User',
                    'pk' => 'user_id',
                    'key_time' =>'created_time'
                ),
                'jointabel' => array(
                    'tname' => '{{company_category}}',
                    'key_c' => 'categoryId',
                    'key_i' => 'companyId'
                )
            ),
        );
    }

}

?>