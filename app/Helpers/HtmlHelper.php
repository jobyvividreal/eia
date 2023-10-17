<?php

namespace App\Helpers;

use App\Models\DocumentStatus;
use App\Models\EiaStage;

class HtmlHelper
{
    public static function createLinkButton($url = null, $value = 'Create') {
        return '<a class="btn mb-1 waves-effect waves-light cyan" href="'.$url.'" name="action">'.$value.'<i class="material-icons right">add</i></a>';
    }

    public static function createAjaxButton($value = 'Create', $data_id = null) {
        return '<a class="btn mb-1 waves-effect waves-light cyan" href="javascript:" onclick="manageCreateForm(null)" name="action">'.$value.'<i class="material-icons right">add</i></a>';
    }

    public static function listLinkButton($url = null, $value = 'List') {
        return '<a class="btn mb-1 waves-effect waves-light cyan" href="'.$url.'" name="'.$value.'">'.$value.'<i class="material-icons right">list</i></a>';
    }

    public static function submitButton($value = 'Submit', $button_id = null) {
        return '<button type="submit" class="btn indigo" id="'.$button_id.'">'.$value.'</button>';
    }

    public static function resetButton($value = 'Reset', $button_id = null) {
        return '<button type="button" class="btn btn-light" id="'.$button_id.'">'.$value.'</button>';
    }

    public static function editButton($url, $id = null)
    {
        $path = NULL;
        if ($url) {
            $path = '<a href="'.$url.'" class="btn mr-2 cyan tooltipped" data-tooltip="Edit details" data-id="'.$id.'"><i class="material-icons">mode_edit</i></a>';
        }
        return $path;
    }
    public static function editAjaxButton($id = null) {
        $path = NULL;
        if ($id) {
            $path = '<a href="javascript:" class="btn mr-2 cyan tooltipped" data-tooltip="Edit details" data-id="'.$id.'" onclick="manageCreateForm('.$id.')"><i class="material-icons">mode_edit</i></a>';
        }
        return $path;
    }
    
    public static function disableButton($url, $id = null, $title = null)
    {
        $path   = NULL;
        $title = ($title != null) ? $title : 'Disable';
        if ($id) {
            $path = '<a href="javascript:void(0);" class="btn btn-danger btn-sm btn-icon mr-2 tooltipped disable-item" data-title="'.$title.'" data-tooltip="Disable" data-id="'.$id.'" data-url="'.$url.'"><i class="material-icons">block</i></a>';
        }
        return $path;
    }

    public static function deleteButton($url, $id = null)
    {
        $path = NULL;
        if ($id) {
            $path = '<a href="javascript:void(0);" class="btn btn-danger btn-sm btn-icon mr-2 tooltipped delete-item" data-tooltip="Delete" data-id="'.$id.'" data-url="'.$url.'"><i class="material-icons">block</i></a>';
        }
        return $path;
    }
    
    public static function restoreButton($url, $id = null, $title = null)
    {
        $path = NULL;
        $title = ($title != null) ? $title : 'Restore';
        if ($id) {
            $path = '<a href="javascript:void(0);" class="btn btn-danger btn-sm btn-icon mr-2 tooltipped gradient-45deg-green-teal restore-item" data-title="'.$title.'" data-tooltip="Restore" data-id="'.$id.'" data-url="'.$url.'"><i class="material-icons">restore</i></a>';
        }
        return $path;
    }

    public static function statusText($stageID = null, $status_id = null)
    {
        $statusText     = '';
        $stage          = '';
        $status         = null;
        $status_colors  = array(1 => 'green', 2 => 'yellow', 3 => 'red');
    
        if($stageID) {
            $stage      = EiaStage::where('id', $stageID)->value('name');
        }

        if ($status_id) {
            $status         = DocumentStatus::where('id', $status_id)->select('name', 'color')->first();
        }
        $statusText     .= '<span class="chip '.$status->color.' lighten-5"><span class="'.$status->color.'-text">'.$stage.' - '. $status->name. '</span></span>';
        return $statusText;
    }
    
    // public static function cropButton($value, $width, $height): string
    // {
    //     return '<input type="file" name="' . $value . '" class="form-control form-control-lg mb-2 crop_file" data-name="' . $value . '" data-width="' . $width . '" data-height="' . $height . '">';
    // }

    
    // public static function editOnClickButton($id, $value = 'Edit')
    // {
    //     $data = NULL;
    //     if ($id) {
    //         $data = '<a class="btn btn-sm btn-outline-dark btn-text-primary btn-hover-primary btn-icon mr-2" title="Edit" href="javascript:;" onclick="manageModal('.$id.')"><i class="fa fa-pencil-alt"></i></a>';
    //     }
    //     return $data;
    // }

    // public static function deleteButton($id, $value = 'Delete')
    // {
    //     $data = NULL;
    //     if ($id) {
    //         $data = '<a class="btn btn-sm btn-outline-dark btn-text-danger btn-hover-danger btn-icon mr-2" title="Delete" href="javascript:;" id="' . $id . '" onclick="ajaxDelete(this.id)"><i class="fa fa-times-circle"></i></a>';
    //     }
    //     return $data;
    // }

    // public static function restoreButton($id, $value = 'Active')
    // {
    //     $data = NULL;
    //     if ($id) {
    //         $data = '<a class="btn btn-sm btn-outline-dark btn-text-success btn-hover-success btn-icon mr-2" title="Activate" href="javascript:;" id="' . $id . '" onclick="ajaxActivate(this.id)"><i class="fa fa-check-circle"></i></a>';
    //     }
    //     return $data;
    // }
    // public static function viewButton($path, $value = 'View')
    // {
    //     $data = NULL;
    //     if ($path) {
    //         $data = '<a class="btn btn-sm btn-outline-dark btn-text-success btn-hover-success btn-icon mr-2" title="View" href="' . $path . '"><i class="fa fa-eye"></i></a>';
    //     }
    //     return $data;
    // }

}

