"use strict";

(function () {
  tinymce.PluginManager.add('pfcbutton', function (editor, url) {
    const assetsUrl = url.replace('/assets/js', '/assets');
    editor.addButton('pfcbutton', {
      text: tinyMCEObjectPFC.button_name,
      icon: true,
      image: assetsUrl + '/images/button.png',
      onclick() {
        editor.windowManager.open({
          title: tinyMCEObjectPFC.button_title,
          body: [{
            type: 'listbox',
            name: 'layout',
            label: 'Select Layout',
            values: [{
              text: 'Layout One',
              value: 'layout-one'
            }, {
              text: 'Layout Two',
              value: 'layout-two'
            }],
            value: 'layout-one' // Sets the default
          }, {
            type: 'listbox',
            name: 'post_cat',
            label: 'Select Category',
            values: tinyMCEObjectPFC.post_cat_list
          }, {
            type: 'listbox',
            name: 'orderby',
            label: 'Order By',
            values: [{
              text: 'Author',
              value: 'author'
            }, {
              text: 'Post Title',
              value: 'title'
            }, {
              text: 'Post ID',
              value: 'ID'
            }, {
              text: 'Posted Date',
              value: 'date'
            }, {
              text: 'Menu Order',
              value: 'menu_order'
            }, {
              text: 'Comment Count',
              value: 'comment_count'
            }, {
              text: 'Random',
              value: 'rand'
            }],
            value: 'date' // Sets the default
          }, {
            type: 'listbox',
            name: 'order',
            label: 'Order',
            values: [{
              text: 'DESC',
              value: 'DESC'
            }, {
              text: 'ASC',
              value: 'ASC'
            }],
            value: 'DESC' // Sets the default
          }, {
            type: 'textbox',
            name: 'post_number',
            label: 'Post Number',
            tooltip: 'Use Numeric Value Only',
            value: 5
          }, {
            type: 'textbox',
            name: 'length',
            label: 'Excerpt Length',
            tooltip: 'Use Numeric Value Only. Leave empty to hide excerpt',
            value: 10
          }, {
            type: 'textbox',
            name: 'readmore',
            label: 'Read More Text',
            tooltip: 'Leave empty to hide read more button',
            value: 'Read More'
          }, {
            type: 'checkbox',
            name: 'show_date',
            label: 'Show Posted Date',
            checked: true
          }, {
            type: 'checkbox',
            name: 'show_image',
            label: 'Show Thumbnail Image',
            checked: true
          }, {
            type: 'listbox',
            name: 'image_size',
            label: 'Select Image Size',
            values: tinyMCEObjectPFC.image_sizes
          }],
          onsubmit(e) {
            editor.insertContent('[pfc layout="' + e.data.layout + '" cat="' + e.data.post_cat + '" order_by="' + e.data.orderby + '" order="' + e.data.order + '" post_number="' + e.data.post_number + '" length="' + e.data.length + '" readmore="' + e.data.readmore + '" show_date="' + e.data.show_date + '" show_image="' + e.data.show_image + '" image_size="' + e.data.image_size + '"]');
          }
        });
      }
    });
  });
})();