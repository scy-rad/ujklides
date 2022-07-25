<?php /* zmienne do skryptu (tożsame ze zmiennymi do modalpicture)
        $picture_name   = 'picture_name';
        $picture_name_img = 'picture_name_img';
*/    ?>

<!-- javascript for Responsive FileManager
        ================================================== --> 
    <!-- Placed at the end of the document so the pages load faster --> 


    <!-- VIDEO -->
    <script src="assets/js/jquery.fitvids.min.js" type="text/javascript"></script>
        
    <script>
        function responsive_filemanager_callback(field_id){
            if(field_id){
                console.log(field_id);
                var url=jQuery('#'+field_id).val();

                document.getElementById("<?php echo $picture_name_img; ?>").src=url;
                document.getElementById("<?php echo $picture_name; ?>").src=url;

                //alert('update '+field_id+" with "+url);
                //your code
            }
        }
    </script>

    <script type="text/javascript">

    jQuery(document).ready(function ($) {
        $('.iframe-btn').fancybox({
            'width'	: 880,
            'height'	: 570,
            'type'	: 'iframe',
            'autoScale'   : false
        });
        //
        // Handles message from ResponsiveFilemanager
        //
        function OnMessage(e){
            var event = e.originalEvent;
            // Make sure the sender of the event is trusted
            if(event.data.sender === 'responsivefilemanager'){
                if(event.data.field_id){
                    var fieldID=event.data.field_id;
                    var url=event.data.url;
                            $('#'+fieldID).val(url).trigger('change');
                            $.fancybox.close();

                            // Delete handler of the message from ResponsiveFilemanager
                            $(window).off('message', OnMessage);
                }
            }
        }

        // Handler for a message from ResponsiveFilemanager
        $('.iframe-btn').on('click',function(){
            $(window).on('message', OnMessage);
        });

        $('#download-button').on('click', function() {
            ga('send', 'event', 'button', 'click', 'download-buttons');      
        });
        $('.toggle').click(function(){
            var _this=$(this);
            $('#'+_this.data('ref')).toggle(200);
            var i=_this.find('i');
            if (i.hasClass('icon-plus')) {
            i.removeClass('icon-plus');
            i.addClass('icon-minus');
            }else{
            i.removeClass('icon-minus');
            i.addClass('icon-plus');
            }
        });
    });

    function open_popup(url)
    {
            var w = 880;
            var h = 570;
            var l = Math.floor((screen.width-w)/2);
            var t = Math.floor((screen.height-h)/2);
            var win = window.open(url, 'ResponsiveFilemanager', "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
    }
    </script>