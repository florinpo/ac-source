<?php

class ImageSize {

    public static function getSizes() {
        return array(
            array(
                'id' => 'img100',
                'width' => 100,
                'height' => 100,
                'quality' => 90,
            ),
            array(
                'id' => 'img180',
                'width' => 180,
                'height' => 180,
                'quality' => 80,
            ),
            array(
                'id' => 'img400',
                'width' => 400,
                'height' => 400,
                'quality' => 70,
            )
        );
    }
    
    public static function getProductSizes() {
        return array(
            array(
                'id' => 'img120',
                'size'=>'120',
                'width' => 120,
                'height' => 120,
                'quality' => 90,
            ),
            array(
                'id' => 'img200',
                'size'=>'200',
                'width' => 200,
                'height' => 200,
                'quality' => 80,
            ),
            array(
                'id' => 'img500',
                'size'=>'500',
                'width' => 500,
                'height' => 500,
                'quality' => 70,
            )
        );
    }
    
    public static function getAvatarSizes() {
        return array(
            array(
                'id' => 'img100',
                'width' => 100,
                'height' => 100,
                'quality' => 90,
            ),
            array(
                'id' => 'img180',
                'width' => 180,
                'height' => 180,
                'quality' => 80,
            )
        );
    }
    
     public static function getStoreSizes() {
        return array(
            
            array(
                'id' => 'img80',
                'width' => 80,
                'height' => 80,
                'quality' => 90,
            ),
            
            array(
                'id' => 'img100',
                'width' => 100,
                'height' => 100,
                'quality' => 80,
            ),
            
            array(
                'id' => 'img400',
                'width' => 400,
                'height' => 400,
                'quality' => 70,
            )
        );
    }
    
    public static function getMailboxSizes() {
        return array(
            array(
                'id' => 'img100',
                'width' => 100,
                'height' => 100,
                'quality' => 90,
            ),
            array(
                'id' => 'img400',
                'width' => 400,
                'height' => 400,
                'quality' => 70,
            )
        );
    }
    
    

}

?>