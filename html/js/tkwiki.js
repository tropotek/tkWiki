/**
 * Created by mifsudm on 15/06/16.
 */

/******************************** WIKI Style Script (optional) ********************************/
// NOTE: Edit this as needed for the template
jQuery(function ($) {
  
  /* -- TOC Menu -- */
  var menu = $('.wiki-content');
  if (menu.length && menu.toc) {
    menu.toc({scope: '.wiki-content'});
  }
  
  
  /* -- Mega Menu -- */
  $('.dropdown.mega-dropdown').on('click', function(e) {
    if ($(this).hasClass('open')) {
      $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true,true).slideUp('400');
      $(this).removeClass('open');
    } else {
      $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true,true).slideDown('400');
      $(this).addClass('open');
    }
    return false;
  });
  
  $('.dropdown.mega-dropdown').on('mouseleave', function(e) {
    $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true,true).slideUp('400');
    $(this).removeClass('open');
  });

});


/******************************** WIKI System script (Required) ********************************/
// NOTE: only edit of you know what you are doing 
jQuery(function ($) {
  
  // Save page header trigger
  $('.wiki-save-trigger').on('click', function(e) {
    // TODO: submit the form for the edit page
    submitForm($('#pageEdit').get(0), 'save');
  });

  // Default delete confirmation
  $('.wiki-delete-trigger').on('click', function(e) {
    return confirm('Are you sure you want to Delete this?');
  });

  // Fix disabled menu items
  $('.disabled, .disabled a').on('click', function(e) {
    return false;
  });
  
  $('.wiki-create-url-trigger').on('click', function(e) {
    var title = $('#fid-title').val();
    console.log(title);
    // ajax request a url, checking for duplicates.
    $(this).blur();
    
  });
  
});


/**
 * Submit a form with an event attached so php scripts can fire the event.
 * 
 * @param formElement form
 * @param string action
 * @param string value (optional) If not supplied, action is used.
 */
function submitForm(form, action)
{
    var value = arguments[2] ? arguments[2] : action;
    if (!form) {
        return;
    }
    // Add the action event to a hidden field and value
    var node = document.createElement('input');
    node.setAttribute('type', 'hidden');
    node.setAttribute('name', action);
    node.setAttribute('value', value);
    form.appendChild(node);
    form.submit();
}



