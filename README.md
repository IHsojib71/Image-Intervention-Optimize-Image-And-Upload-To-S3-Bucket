# 🖼️ Image Intervention - Optimize & Upload to S3

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-10%2F11%2F12-red.svg)](https://laravel.com)
[![Intervention Image](https://img.shields.io/badge/Intervention-3.11-green.svg)](https://image.intervention.io)
[![AWS S3](https://img.shields.io/badge/AWS-S3-orange.svg)](https://aws.amazon.com/s3/)

A robust Laravel service for automatically optimizing images and uploading them to AWS S3 bucket. Tested with **Intervention Image v3.11** and **PHP 8.2**.

## ✨ Features

- 🚀 Automatic image optimization (compress without quality loss)
- 📤 Direct upload to AWS S3 bucket
- 🎯 Target file size control
- 🔄 Multiple format support (JPEG, PNG, WebP, GIF)
- 📁 Organized folder structure in S3
- ⚡ Memory efficient processing

## 📋 Requirements

- PHP 8.2 or higher
- Laravel 10.x / 11.x / 12.x
- Intervention Image 3.11
- AWS S3 account & credentials

## 🔧 Installation

### 1. Install Package

```bash
composer require intervention/image
```
### 2. How to use

```bash  
protected $imageUploadService;
public function __construct(ImageUploadService $imageUploadService)
{
    $this->imageUploadService = $imageUploadService;
}

//To call this and our files from request 
 $valid = $request->validated();
 if($valid['image'])
      {
          // Process and upload the image
          $valid['image'] = $this->imageUploadService->optimizeAndUpload(
              $request->file('image'),
              'ProductImage', // Folder in S3
              512      // Target size in KB
          );
   }
  
  





