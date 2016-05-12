/**
 * Created by blueset on 25/4/16.
 */

$(function () {
   $("[data-type=tags]").each(function () {
       var tags = $(this);
       var fmin = tags.data('min');
       var fmax = tags.data('max');
       var nmax = 1;
       var nmin = 1;
       tags.children('[data-type=tag]').each(function () {
           if (parseInt($(this).data('count')) > nmax) {
               nmax = parseInt($(this).data('count'));
           }
           if (parseInt($(this).data('count')) < nmin) {
               nmin = parseInt($(this).data('count'));
           }
       });
       tags.children('[data-type=tag]').each(function () {
           /*
            * 1. a = (nmax - nmin + 1): overall post count range
            * 2. b = parseInt($(this).data('count')) - nmin: count of current tag
            * 3. c = b * 2 + 1 / a: mid value of the chosen segment
            * 4. fmin + (fmax - fmin) * c: calculated font size in em.
            */
           var val = fmin + (fmax - fmin) * (((parseInt($(this).data('count')) - nmin) * 2 + 1) / ((nmax - nmin + 1) * 2));
           $(this).css("font-size", val + "em");
       });

   });
});