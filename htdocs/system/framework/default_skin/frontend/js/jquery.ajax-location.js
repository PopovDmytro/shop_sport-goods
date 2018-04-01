// if (typeof(window.history.pushState) == 'function') {
//     var title = $('title:first');
//     var keywords = $('meta[name=keywords]:first');
//     var description = $('meta[name=description]:first');

//     var onLocation = function(data){
//         if (typeof(data) != 'object' || typeof(data._seo) != 'object') {
//             alert('Location format error');
//             return false;
//         }
//         if (title.length) {
//             title.html(data._seo.title);
//         }
//         if (keywords.length) {
//             keywords.attr('content', data._seo.keywords);
//         }
//         var $docErrors = $('div#doc_errors');
//         if ($docErrors.length) {
//             $docErrors.remove();
//         }
//         if (description.length) {
//             description.attr('content', data._seo.description);
//         }
//         for (i in data) {
//             if (i.substr(0, 1) != '_') {
//                 var block_id = 'active_template_content';
//                 if (i != 'default') {
//                     block_id += '_'+i;
//                 }
//                 var block = $('#'+block_id);
//                 if (block.length) {
//                     block.html(data[i]);
//                 }
//             }
//         }
//         return true;
//     };

//     jQuery('a').live('click', function(event){
//         var layout = $(this).attr('layout');
//         var url = $(this).attr('href');
//         if (layout == window.layout && url.indexOf(':') < 0) {
//             $.rexLocation(url, function(data){
//                 if (onLocation(data)) {
//                     window.history.pushState('rex_location', null, url);
//                     jQuery('html, body').animate({scrollTop: 0}, 300)
//                 }
//             });
//             event.preventDefault();
//         }
//     });

//     window.addEventListener('popstate', function(e){
//         if (e.state == 'rex_location') {
//             $.rexLocation(e.target.location.toString(), onLocation);
//         }
//     }, false);
// }