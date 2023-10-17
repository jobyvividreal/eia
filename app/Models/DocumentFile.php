<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFile extends Model
{
    use HasFactory;

    public function getShowFilenameAttribute()
    {
        return ($this->name != '') ? asset('storage/documents/' . $this->name) : asset('admin/images/image-not-found.png');
    }

    public function getFilePreviewAttribute($name)
    {
        $doc_extensions = ['docx', 'doc'];
        $xls_extensions = ['xls', 'xlsx'];

        if(pathinfo($this->name, PATHINFO_EXTENSION) == 'pdf') {
            $name = asset('admin/images/demo_pdf.png') ;
        } elseif(in_array(pathinfo($this->name, PATHINFO_EXTENSION), $doc_extensions)) {
            $name = asset('admin/images/demo_doc.png') ;
        } elseif(in_array(pathinfo($this->name, PATHINFO_EXTENSION), $xls_extensions)) {
            $name = asset('admin/images/demo_xls.png') ;
        } else {
            $name = ($this->name != null) ? asset('storage/documents/' . $this->name) : asset('admin/images/image-not-found.png');
        } 
        return $name;
    }


    public function getFileViewAttribute($name)
    {
        $doc_extensions = ['docx', 'doc'];
        $xls_extensions = ['xls', 'xlsx'];

        if(pathinfo($this->name, PATHINFO_EXTENSION) == 'pdf') {
            $name = ($this->name != null) ? asset('storage/documents/' . $this->name) : asset('admin/images/image-not-found.png');
        } elseif(in_array(pathinfo($this->name, PATHINFO_EXTENSION), $doc_extensions)) {
            
            $name = route('documents.download', $this->name);
            
        } elseif(in_array(pathinfo($this->name, PATHINFO_EXTENSION), $xls_extensions)) {
            $name = route('documents.download', $this->name);
        } else {
            $name = ($this->name != null) ? asset('storage/documents/' . $this->name) : asset('admin/images/image-not-found.png');
        } 
        return $name;
    }

    public function getFileDownloadAttribute($name)
    {
        // $doc_extensions = ['docx', 'doc'];
        // $xls_extensions = ['xls', 'xlsx'];

        // if(pathinfo($this->name, PATHINFO_EXTENSION) == 'pdf') {
        //     $name = ($this->name != null) ? url('documents/pdf-download/' . $this->name) : 'javascript:';
        // } elseif(in_array(pathinfo($this->name, PATHINFO_EXTENSION), $doc_extensions)) {
            
        //     $name = asset('admin/images/demo_doc.png') ;

        // } elseif(in_array(pathinfo($this->name, PATHINFO_EXTENSION), $xls_extensions)) {
            
        //     $name = asset('admin/images/demo_xls.png') ;
        // } else {
        //     $name = ($this->name != null) ? url('documents/image-download/'.$this->name) : 'javascript:';
        // } 
        // return $name;
    }
}
