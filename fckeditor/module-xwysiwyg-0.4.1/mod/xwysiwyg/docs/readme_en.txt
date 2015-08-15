Preface:
The xwysiwyg-module requires significant changes in the 'Core' files of phpWebSite. You should only install this module if you are experienced with PHP and phpWebSite. If you upgrade phpWebSite after installing the xwysiwyg-module, the module may not function properly. In contrast to other htmlArea hacks that were published previously, you don't need to change installed modules. Only the file /js/wysiwyg.php is affected.

Function
The xwysiwyg module enhances phpWebSite with open-source wysiwyg editors (What You See Is What You Get) for text fields and text areas. At this time, xwysiwyg supports HtmlArea, Xinha, FCKeditor and TinyMCE. This module should in theory work with IE5.5+, NS7+, MZ1.4+, FB0.6+ and FX0.10+. Compatibility may depend on the editor used.

Usage:
You can change some settings of the xwysiwyg module using the Control Panel. There, you can select the editor you prefer. You can specify if the tool is only available to registered users or if you want to allow anonymous users to use it too.  Additionally you can activate plugins in some editors. Some plugins may not work with this module, so some experiment may be needed. Some editors let you select a theme/skin.

After installing the module, the phpWebSite interface looks the same as usual, except there is a new XW button in the wysiwyg panel above text fields and text areas. To activate xwysiwyg for a specified area, click this button. ATTENTION: any unsaved text will be lost!  Alternatively, you can select the Request Mode of xwysiwyg, and use the XW button to start the editor on request.

How it works:
Five parts working together do what xwysiwyg does...
1.	The wysiwyg-hack. This is the single modified phpws core file - /js/wysiwyg.php. The modifications are needed to to call xwysiwyg for each text area it serves.
2.	The xwysiwyg module. This is a standard module package to be installed in phpws using Boost. It provides a settings screen, database maintenance, and access to the editor helper files on request. It also provides facilities for downloading and installing your editor(s) of choice.
3.	The editor helper file. Part of the editor package, editor_rel.php (Editor, Release) generates javascript needed for the editor to work in a phpws website.
4.	The editor configuration file. Also part of the editor package, editor_rel.conf (Editor, Release) is used once while installing the editor into phpws using the xwysiwyg module. It contains information about the editor name, default path, available plugins, and available skins.
5.	The editor. This is normally installed unmodified, in other words 'as delivered' from its developer. It often has to be configured to suit the user's needs.

Parts 1 and 2 are delivered as the xwysiwyg module on sourceforge/projects/phpwebsite-comm.
Parts 3, 4 and 5 are delivered together in editor packages downloadable from the xwysiwyg Manage Editor screen.

More about the editor_xxx.conf file (e.g., fck_xxx.conf):
Edit this configuration file BEFORE installing the editor into xwysiwyg. The settings can later be changed on the xwysiwyg settings screen. The only reason to edit this file in general is to make available plugins and/or skins not included in the original configuration file.

Known Issues
- HtmlArea, with lot of plugins activated, may not start sometimes, and shows some Javascript errors. Try to reload the page a second time (via a link, not the refresh button).
- This module will have problems with servers running in Safe Mode, because it create files and folders. Read SafeMode.txt for further instructions.
- There is a Safe Mode setting for Xinhas-ImageManager. See /path_to_xinha/plugins/ImageManager/config.inc.php for this setting. The plugin will lose the ability to create folders. 
- If you do use InsertFile and ImageManager, make sure that there are correct permissions in images and document directories.  (The web server must be able to write to these directories.)

yk&gb nov. 2005
