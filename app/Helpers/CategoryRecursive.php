<?php
namespace App\Helpers;

class CategoryRecursive {
    // function displayCategoryOptions($categories, $catId, $parentId = '', $level = '') {
    //     $htmlOptions = '';

    //     foreach($categories as $item) {
    //         if($item['parent_id'] == $parentId) {

    //             if(!empty($catId) && $item['id'] == $catId) {

    //                 $htmlOptions .= '<option selected value="'. $item['id'] .'">'. $level . $item['name'] .'</option>';
                    
    //             } else {
    //                 $htmlOptions .= '<option value="'. $item['id'] .'">'. $level . $item['name'] .'</option>';
    //             }
    //             $htmlOptions .= $this->displayCategoryOptions($categories, $catId, $item['id'], $level . '--');
    //         }
    //     }
    //     return $htmlOptions;
    // }


    function displayCategoryOptions($categories, $selectedCategories = [], $parentId = '', $level = '') {
        $htmlOptions = '';
    
        foreach ($categories as $item) {
            if ($item['parent_id'] == $parentId) {
                // Kiểm tra xem danh mục này có nằm trong danh sách đã chọn hay không
                $selected = in_array($item['id'], $selectedCategories) ? 'selected' : '';
    
                $htmlOptions .= '<option ' . $selected . ' value="' . $item['id'] . '">' . $level . $item['name'] . '</option>';
    
                $htmlOptions .= $this->displayCategoryOptions($categories, $selectedCategories, $item['id'], $level . '--');
            }
        }
    
        return $htmlOptions;
    }
}

