Vorwort:
Das xwysiwyg-Modul beinhaltet einen Eingriff in die 'Core'-Dateien des phpWebSite-Projekts. Installieren Sie dieses Modul nur, wenn Sie einige Erfahrung im Umgang mit PHP und phpWebSite haben. Sollte Sie nach der Installation dieses Moduls die phpWebSite upgraden, kann es sein, das das Modul nicht mehr ordnungsgemaess funktioniert. Entgegen den bisher veroeffentlichten htmlArea-Hacks, sind bei diesem Modul keine Eingriffe in andere installierten Module notwendig. Einzig die Datei /js/wysiwyg.php ist betroffen.

Funktion
Das xwysiwyg-Modul erweitert Ihre PhpWebSite mit frei erhaeltlichen Wysiwyg-Editoren (What You See Is What You Get). Zur Zeit sind das z.B. HtmlArea, Xinha, FCKeditor und ev. TinyMCE. Dieses Modul funktioniert theoretisch mit IE5.5+, NS7+, MZ1.4+, FB0.6+ und FX 1+ Die Kompatibilitaet ist jedoch vom verwendeten Editor abhaengig.

Verwendung
Im Administrations-Bereich Ihrer phpWebSite (Controlpanel) koenne Sie einige Einstellungen am xwysiwyg-Modul vornehmen. Waehlen Sie dort den gewuenschten Editor aus (HtmlArea oder FCKeditor). Legen Sie dort fest, ob das Tool nur fuer angemeldete Benutzer oder auch anonymen Besuchern zur Verfuegung steht.

Im Administrations-Bereich Ihrer phpWebSite koenne Sie einige Einstellungen am xwysiwyg-Modul vornehmen. Waehlen Sie dort den gewuenschten Editor aus. Legen Sie dort fest, ob das Tool nur fuer angemeldete Benutzer oder auch anonymen Besuchern zur Verfuegung steht. Dort koennen Sie auch allfaellig vorhandene Plugins aktivieren. Nicht alle Pugins funktionieren mit diesem Modul, ev. muessen Sie etwas experimentieren. Fuer einige Editore koennen Sie die Oberflaeche auswaehlen.

Wie's funktioniert:
Oder: wie sind die Abhaengigkeiten der xwysiwyg-Teile...
5 Teile arbeiten zusammen, um den gewuenschten Effekt zu erzielen.
>1.	Der wysiwyg-Hack. Dies ist eine modifizierte phpws-Core-Datei. Sie befindet
	sich in /js und heisst wysiwyg.php . Die Aenderungen haben den Zweck das
	xwysiwyg-Modul bei Bedarf aufzurufen.
>2.	Das xwysiwyg-Modul. Es wird in phpws installiert und verwaltet die Einstellungen,
	pflegt die Datenbank und ruft wenn Noetig die Editor-Hilfs-Dateien auf request.
>3.	Die Editor-Hilfs-Datei. Sie heisst editor_rel.php (Editor, Release). 
	Sie generiert die noetigen Javascript-Zeilen in der phpws Webseite.
>4.	Die Editor-Config-Datei. Sie heisst editor_rel.conf (Editor, Release).
	Sie wird einmalig beim installieren des Editors in phpws (xwysiwyg-module)
	benutzt. Sie enthaelt wichtige Informationen ueber den Editor-Namen,
	Standard-Pfad, verfuegbare Plugins oder verfuegbare Oberflaechen (Skins).
>5.	Der Editor. Er wird normalerweise unbearbeitet, oder mit anderen Worten 
	'wie vom Entwickler angeboten' mitgeliefert. Er muss oftmals den Umstaenden
	der phpws Installation angepasst/konfiguriert werden.

Die Teile 1 und 2 werden mit dem xwysiwyg-Modul auf sourceforge/projects/phpwebsite-comm zum Download angeboten.
Die Teile 3, 4 und 5 werden zusammen als so genannte editor-packages im xw-Manager-Fenster zum Download angeboten.

Weitere Infos zur editor_xxx.conf Datei:
Die (als Beispiel) fck_xxx.conf muss bei Bedarf VOR dem Installieren des Editors ins xwysiwyg-Modul angepasst werden. Die enthaltenen Einstellungen koennen spaeter im xw-Einstellungs-Fenster geaendert werden. Der haeufigste Grund fuer eine Anpassung dieser Datei ist, um eine andere Auswahl an Plugins oder Skins zur Verfuegung zu stellen als in der Original-Datei.

Bekannte Probleme
-Es ist bekannt, dass HtmlArea mit vielen aktivierten Plugins manchmal nicht startet und einen Javascript-Fehler anzeigt. Versuchen Sie diese Seite nochmals zu laden (via ein Link nicht mit refresh).
-Das Modul hat Probleme mit SafeMode-Servern, da Dateien und Ordner angelegt werden moechten. Lesen Sie SafeMode.txt fuer weitere Informationen.
-Fuer den Xinha/htmlarea-ImageManager gibt eine SafeMode-Einstellung in /pfad_zu_xinha/plugins/ImageManager/config.inc.php. Dadurch koennen aber keine Verzeichnisse mehr angelegt werden. Stellen Sie zudem sicher, dass Sie genuegend Rechte in Ihren Ordnern erteilt haben.

yk November 2005