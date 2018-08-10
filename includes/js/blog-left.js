jQuery(function($) {
    $(document).ready(function() {
    if('objectFit' in document.documentElement.style === false) {
    //see if this is a blog page where we are applying our custom function
    var blog_img_left = $('.et_pb_posts .et_pb_post a img');
    if(blog_img_left.length > 0){
        // assign HTMLCollection with parents of images with objectFit to variable
        blog_img_left.each(function() {
            $(this).css('height', 'auto');
        });
        } //end if(blog_img_left.length)
    }//end if('objectFit' in document.documentElement.style === false)
    });
});
