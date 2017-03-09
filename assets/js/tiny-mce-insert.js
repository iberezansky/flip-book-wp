
tinymce.PluginManager.add('3dfbInsert', function(editor) {

	function showDialog() {
		var shortCode, insert;
		function loadInsertApp() {
      if(fb3dCreateInsertApp) {
				var instance, win = editor.windowManager.open({
					title: "3D FlipBook",
					spacing: 10,
					padding: 10,
					items: [
						{
							minWidth: 450,
							minHeight: 400,
							type: 'container',
							html: '<div id="3dfb-insert">Mount node</div>'
						},
					],
					buttons: [{
						text: "Close",
						onclick: function() {
							win.close();
						}
					}, {
						text: "Ok",
						subtype: 'primary',
						onclick: function() {
							var newShortCode = instance.getShortCode();
							if(newShortCode!=='') {
								insert(newShortCode);
								win.close();
							}
							else {
								editor.windowManager.alert('Select a 3D FlipBook');
							}
						}
					}]
				});
				instance = fb3dCreateInsertApp(jQuery('#'+win._id).find('#3dfb-insert')[0], shortCode);
      }
      else if(time<5000) {
				var dt = 500;
				time += dt;
        setTimeout(loadInsertApp, dt);
      }
			else {
				editor.windowManager.alert('Cannot connect to the insert application');
			}
    }

		var sel = editor.selection.getSel();
		if(sel.focusNode!==sel.anchorNode || sel.focusOffset!==sel.anchorOffset) {
			editor.windowManager.alert('Please reset multiple selection and place the cursor in a short code or a free space');
		}
		else {
			var text = sel.focusNode.nodeValue || '', p = text.lastIndexOf('[',sel.focusOffset);
			if(~p && text.lastIndexOf(']',sel.focusOffset)<p && text.substr(p).match(/\[.*?\]/)) {
				var startText = text.substr(0, p);
				text = text.substr(p);
				var regex = new RegExp(['\\[',FB3D_MCE_LOCALE.key,'.*?\\]'].join(''), 'm'), matchs = regex.exec(text);
				if(matchs && text.indexOf(matchs[0])===0) {
					shortCode = matchs[0];
					var offset = sel.focusOffset, node = sel.focusNode;
					if(shortCode.match(/mode\s*=\s*['"]{0,1}link-lightbox['"]{0,1}/)) {
						regex = new RegExp(['\\[',FB3D_MCE_LOCALE.key,'.*?\\[/', FB3D_MCE_LOCALE.key, '\\]'].join(''), 'm');
						matchs = regex.exec(text);
						if(matchs) {
							shortCode = matchs[0];
						}
					}
					insert = function(newShortCode) {
						node.nodeValue = startText+text.replace(shortCode, newShortCode);
						sel.collapse(node, Math.min(offset, startText.length+newShortCode.length));
					};

					loadInsertApp();
				}
				else {
					editor.windowManager.alert('This short code is unknown for 3D FlipBook');
				}
			}
			else {
				shortCode = '';
				insert = function(newShortCode) {
					editor.execCommand('mceInsertContent', false, newShortCode);
				};
				loadInsertApp();
			}
		}
	}

	editor.addCommand('mceShow3dfbInsert', showDialog);

	editor.addButton('3dfbInsert', {
		icon: 'newdocument',
		image: FB3D_MCE_LOCALE.icon,
		tooltip: '3D FlipBook',
		cmd: 'mceShow3dfbInsert'
	});

	editor.addMenuItem('3dfbInsert', {
		icon: 'newdocument',
		image: FB3D_MCE_LOCALE.icon,
		text: '3D FlipBook',
		cmd: 'mceShow3dfbInsert',
		context: 'insert'
	});

	return {
	};
});
