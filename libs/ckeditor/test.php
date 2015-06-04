<script src="ckeditor.js"></script>
<link rel="stylesheet" href="sample.css">
<textarea cols="80" id="editor1" name="editor1" rows="10"></textarea>
                    <script type="text/javascript">
                        
                        CKEDITOR.replace( 'editor1',
                        {
                            filebrowserBrowseUrl :'http://localhost/ckeditor/filemanager/browser/default/browser.html?Connector=http://localhost/ckeditor/filemanager/connectors/php/connector.php',
                            filebrowserImageBrowseUrl : 'http://localhost/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=http://localhost/ckeditor/filemanager/connectors/php/connector.php',
                            filebrowserFlashBrowseUrl :'http://localhost/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=http://localhost/ckeditor/filemanager/connectors/php/connector.php',
                            filebrowserUploadUrl  :'http://localhost/ckeditor/filemanager/connectors/php/upload.php?Type=File',
                            filebrowserImageUploadUrl : 'http://localhost/ckeditor/filemanager/connectors/php/upload.php?Type=Image',
                            filebrowserFlashUploadUrl : 'http://localhost/ckeditor/filemanager/connectors/php/upload.php?Type=Flash'
                        });

                        //]]>
                    </script>