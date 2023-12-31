<?php
/* $Id: albanian-utf-8.inc.php,v 1.2 2005/10/29 20:08:11 kaloyan_raev Exp $ */

/**
 * Translated by: Laurent Dhima <laurenti at users.sourceforge.net>
 * Rishikuar nga: Arben Çokaj <acokaj at t-online.de>
 */

$charset = 'utf-8';
$allow_recoding = TRUE;
$text_dir = 'ltr';
$left_font_family = 'verdana, arial, helvetica, geneva, sans-serif';
$right_font_family = 'arial, helvetica, geneva, sans-serif';
$number_thousands_separator = '.';
$number_decimal_separator = ',';
// shortcuts for Byte, Kilo, Mega, Giga, Tera, Peta, Exa
$byteUnits = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

$day_of_week = array('Djl', 'Hën', 'Mar', 'Mër', 'Enj', 'Pre', 'Sht'); //albanian days
$month = array('Jan', 'Shk', 'Mar', 'Pri', 'Maj', 'Qer', 'Kor', 'Gsh', 'Sht', 'Tet', 'Nën', 'Dhj'); //albanian months
// See http://www.php.net/manual/en/function.strftime.php to define the
// variable below
$datefmt = '%d %B, %Y at %I:%M %p'; //albanian time
$timespanfmt = '%s ditë, %s orë, %s minuta dhe %s sekonda';

$strAPrimaryKey = 'Një kyç primar u shtua tek %s';
$strAbortedClients = 'Dështoi';
$strAbsolutePathToDocSqlDir = 'Ju lutem, shkruani pozicionin absolut në webserver për tek directory e docSQL';
$strAccessDenied = 'Hyrja nuk u pranua';
$strAction = 'Aksioni';
$strAddDeleteColumn = 'Shto/Fshi fushën';
$strAddDeleteRow = 'Shto/Fshi kriterin';
$strAddNewField = 'Shto një fushë të re';
$strAddPriv = 'Shto një privilegj të ri';
$strAddPrivMessage = 'Ke shtuar një privilegj të ri.';
$strAddPrivilegesOnDb = 'Shto privilegje tek databazë në vazhdim';
$strAddPrivilegesOnTbl = 'Shto privilegje tek tabela në vazhdim';
$strAddSearchConditions = 'Shto kushte kërkimi (trupi i specifikimit "where"):';
$strAddToIndex = 'Shto tek treguesi i &nbsp;%s&nbsp;kolonës(ave)';
$strAddUser = 'Shto një përdorues të ri';
$strAddUserMessage = 'Ke shtuar një përdorues të ri.';
$strAddedColumnComment = 'Komenti u shtua në kollonë';
$strAddedColumnRelation = 'Relacioni u shtua për kollonën';
$strAdministration = 'Administrimi';
$strAffectedRows = 'Rreshtat e ndikuar:';
$strAfter = 'Mbas %s';
$strAfterInsertBack = 'Mbrapa';
$strAfterInsertNewInsert = 'Shto një record të ri';
$strAll = 'Të gjithë';
$strAllTableSameWidth = 'vizualizon të gjitha tabelat me të njëjtën gjërësi?';
$strAlterOrderBy = 'Transformo tabelën e renditur sipas';
$strAnIndex = 'Një tregues u shtua tek %s';
$strAnalyzeTable = 'Analizo tabelën';
$strAnd = 'Dhe';
$strAny = 'Çfarëdo';
$strAnyColumn = 'Çfarëdo kolone';
$strAnyDatabase = 'Çfarëdo databazë';
$strAnyHost = 'Çfarëdo host';
$strAnyTable = 'Çfarëdo tabelë';
$strAnyUser = 'Çfarëdo përdorues';
$strAscending = 'Ngjitje';
$strAtBeginningOfTable = 'Në fillim të tabelës';
$strAtEndOfTable = 'Në fund të tabelës';
$strAttr = 'Pronësi';
$strAutodetect = 'Autozbulim';
$strAutomaticLayout = 'Faqosje automatike';

$strBack = 'Mbrapa';
$strBeginCut = 'FILLIMI I CUT';
$strBeginRaw = 'FILLIMI I RAW';
$strBinary = 'Binar';
$strBinaryDoNotEdit = 'Të dhëna të tipit binar - mos modifiko';
$strBookmarkDeleted = 'Bookmark u fshi.';
$strBookmarkLabel = 'Etiketë';
$strBookmarkQuery = 'Query SQL shtuar të preferuarve';
$strBookmarkThis = 'Shtoja të preferuarve këtë query SQL';
$strBookmarkView = 'Vizualizo vetëm';
$strBrowse = 'Shfaq';
$strBzError = 'phpMyAdmin nuk është në gjendje të kompresojë dump-in për arsye të ekstensionit Bz2 të gabuar në këtë version të php. Ju rekomandojmë patjetër setimin e <code>$cfg[\'BZipDump\']</code> tek file juaj i konfigurimit të phpMyAdmin në <code>FALSE</code>. Nëqoftëse dëshironi të përdorni patjetër specifikat e kompresimit Bz2, duhet të rifreskoni php në versionin e fundit. Hidhini një sy php bug report %s për informacione të hollësishme.';
$strBzip = '"kompresuar me bzip2"';

$strCSVOptions = 'Opcione CSV';
$strCannotLogin = 'E pamundur kryerja e login tek server-i MySQL';
$strCantLoad = 'I pamundur ngarkimi i ekstesionit %s,<br />kontrollo konfigurimin e PHP';
$strCantLoadMySQL = 'nuk arrij të ngarkoj ekstensionin MySQL,<br />kontrollo konfigurimin e PHP.';
$strCantLoadRecodeIconv = 'I pamundur ngarkimi i ekstensionit iconv apo recode të nevoitshëm për konvertimin e karaktereve, konfiguroni php për të lejuar përdorimin e këtyre ekstensioneve ose disaktivoni konvertimin e set të karaktereve në phpMyAdmin.';
$strCantRenameIdxToPrimary = 'I pamundur riemërtimi i treguesit në PRIMAR!';
$strCantUseRecodeIconv = 'I pamundur përdorimi i funksioneve iconv apo libiconv apo recode_string për shkak se ekstensioni duhet të ngarkohet. Kontrolloni konfigurimin e php.';
$strCardinality = '';
$strCarriage = 'Kthimi në fillim: \\r';
$strChange = 'Modifiko';
$strChangeCopyMode = 'Krijo një përdorues të ri me të njëjta të drejta dhe ...';
$strChangeCopyModeCopy = '... mbaj të vjetrin.';
$strChangeCopyModeDeleteAndReload = ' ... elemino të vjetrin nga tabela e përdoruesve e pastaj rilexo të drejtat.';
$strChangeCopyModeJustDelete = ' ... elemino të vjetrin nga tabela e përdoruesve.';
$strChangeCopyModeRevoke = ' ... hiq të gjitha të drejtat nga i vjetri e pastaj eleminoje.';
$strChangeCopyUser = 'Ndrysho Informacionet e Login / Kopjo përdoruesin';
$strChangeDisplay = 'Zgjidh fushën që dëshiron të shohësh';
$strChangePassword = 'Ndrysho password';
$strCharsetOfFile = 'Set karakteresh të file:';
$strCheckAll = 'Seleksionoi të gjithë';
$strCheckDbPriv = 'Kontrollo të drejtat e databazës';
$strCheckPrivs = 'Kontrollo të drejtat';
$strCheckPrivsLong = 'Kontrollo të drejtat për databazën &quot;%s&quot;.';
$strCheckTable = 'Kontrollo tabelën';
$strChoosePage = 'Ju lutem zgjidhni faqen që dëshironi të modifikoni';
$strColComFeat = 'Vizualizimi i komenteve të kollonave';
$strColumn = 'Kollona';
$strColumnNames = 'Emrat e kollonave';
$strColumnPrivileges = 'Privilegjet relative të kollonave';
$strCommand = 'Komanda';
$strComments = 'Komente';
$strCompleteInserts = 'Të shtuarat komplet';
$strCompression = 'Kompresim';
$strConfigFileError = 'phpMyAdmin nuk arrin të lexojë file e konfigurimit!<br />Kjo mund të ndodhë kur php gjen një parse error në të apo kur php nuk arrin ta gjejë këtë file.<br />Ju lutem ngarkoheni direkt file e konfigurimit duke përdorur link-un e mëposhtëm dhe lexoni mesazhin(et) e gabimeve php që merrni. Në shumicën e rasteve mund t\'ju mungojë një apostrofë apo një presje.<br />Nëse faqja që do t\'ju hapet është bosh (e bardhë), atëhere gjithçka është në rregull.';
$strConfigureTableCoord = 'Ju lutem, konfiguroni koordinatat për tabelën %s';
$strConfirm = 'I sigurt që dëshiron ta bësh?';
$strConnections = 'Lidhje';
$strCookiesRequired = 'Nga kjo pikë e tutje, cookies duhet të jenë të aktivuara.';
$strCopyTable = 'Kopjo tabelën tek (databazë<b>.</b>tabela):';
$strCopyTableOK = 'Tabela %s u kopjua tek %s.';
$strCouldNotKill = 'phpMyAdmin nuk është në gjendje të përfundojë thread %s. Ka mundësi të ketë përfunduar më parë.';
$strCreate = 'Krijo';
$strCreateIndex = 'Krijo një tregues tek &nbsp;%s&nbsp;columns';
$strCreateIndexTopic = 'Krijo një tregues të ri';
$strCreateNewDatabase = 'Krijo një databazë të re';
$strCreateNewTable = 'Krijo një tabelë të re tek databazë %s';
$strCreatePage = 'Krijo një faqe të re';
$strCreatePdfFeat = 'Krijimi i PDF-ve';
$strCriteria = 'Kriteri';

$strDBComment = 'Komenti për Databazën: ';
$strDBGContext = 'Konteksti';
$strDBGContextID = 'ID e kontekstit';
$strDBGHits = 'Hits';
$strDBGLine = 'Rreshti';
$strDBGMaxTimeMs = 'Koha maksimum, ms';
$strDBGMinTimeMs = 'Koha minimum, ms';
$strDBGModule = 'Modul';
$strDBGTimePerHitMs = 'Koha/Hit, ms';
$strDBGTotalTimeMs = 'Koha gjithsej, ms';
$strData = 'Të dhëna';
$strDataDict = 'Data Dictionary';
$strDataOnly = 'Vetëm të dhëna';
$strDatabase = 'Databazë ';
$strDatabaseHasBeenDropped = 'Databaza %s u eleminua.';
$strDatabaseWildcard = 'Database (wildcards e lejuara):';
$strDatabases = 'databazë';
$strDatabasesDropped = '%s databases u eleminuan korrektësisht.';
$strDatabasesStats = 'Statistikat e databases';
$strDatabasesStatsDisable = 'Deaktivo Statistikat';
$strDatabasesStatsEnable = 'Aktivo Statistikat';
$strDatabasesStatsHeavyTraffic = 'Shënim: Aktivimi këtu i statistikave të Databazës mund të shkaktojë rritjen e trafikut midis server-it web dhe MySQL.';
$strDbPrivileges = 'Privilegje specifike të databazës';
$strDbSpecific = 'specifik i databazës';
$strDefault = 'Paracaktuar';
$strDefaultValueHelp = 'Për vlerat default, ju lutem shtoni një vlerë të vetme, pa backslash escaping apo thonjëza, duke përdorur këtë format: a';
$strDelOld = 'Faqja aktuale përmban Riferime ndaj Tabelash që nuk ekzistojnë më. Dëshironi t\'i eleminoni këto Riferimente?';
$strDelete = 'Fshi';
$strDeleteAndFlush = 'Fshi përdoruesit dhe pastaj rilexo privilegjet.';
$strDeleteAndFlushDescr = 'Kjo është rruga më e pastër, por ngarkimi i privilegjeve mund të zgjasë disa çaste më shumë.';
$strDeleteFailed = 'Fshirja dështoi!';
$strDeleteUserMessage = 'Ke fshirë përdoruesin %s.';
$strDeleted = 'rreshti u fshi';
$strDeletedRows = 'rreshtat e fshirë:';
$strDeleting = 'Në fshirje e sipër të %s';
$strDescending = 'Zbritës';
$strDisabled = 'Disaktivuar';
$strDisplay = 'Vizualizo';
$strDisplayFeat = 'Vizualizo karakteristikat';
$strDisplayOrder = 'Mënyra e vizualizimit:';
$strDisplayPDF = 'Shfaq skemën e PDF';
$strDoAQuery = 'Zbato "query nga shembull" (karakteri jolly: "%")';
$strDoYouReally = 'Konfermo: ';
$strDocu = 'Dokumentet';
$strDrop = 'Elemino';
$strDropDB = 'Elemino databazën %s';
$strDropSelectedDatabases = 'Elemino Databazat e Zgjedhura';
$strDropTable = 'Elemino tabelën';
$strDropUsersDb = 'Elemino databazat që kanë emër të njëjtë me përdoruesit.';
$strDumpComments = 'Përfshi komentet e kollonave si komente-SQL në linjë';
$strDumpXRows = 'Dump i %s rreshta duke filluar nga rreshti %s.';
$strDumpingData = 'Dump i të dhënave për tabelën';
$strDynamic = 'dinamik';

$strEdit = 'Modifiko';
$strEditPDFPages = 'Modifiko Faqe PDF';
$strEditPrivileges = 'Modifiko Privilegjet';
$strEffective = 'Efektiv';
$strEmpty = 'Zbraz';
$strEmptyResultSet = 'MySQL ka kthyer një të përbashkët boshe (p.sh. zero rreshta).';
$strEnabled = 'Aktivuar';
$strEnd = 'Fund';
$strEndCut = 'FUNDI I CUT';
$strEndRaw = 'FUNDI I RAW';
$strEnglishPrivileges = 'Shënim: emrat e privilegjeve të MySQL janë në Anglisht';
$strError = 'Gabim';
$strExplain = 'Shpjego SQL';
$strExport = 'Eksporto';
$strExportToXML = 'Eksporto në formatin XML';
$strExtendedInserts = 'Të shtuara të zgjeruara';
$strExtra = 'Extra';

$strFailedAttempts = 'Përpjekje të dështuara';
$strField = 'Fushë';
$strFieldHasBeenDropped = 'Fusha %s u eleminua';
$strFields = 'Fusha';
$strFieldsEmpty = ' Numratori i fushave është bosh! ';
$strFieldsEnclosedBy = 'Fushë e përbërë nga';
$strFieldsEscapedBy = 'Fushë e kufizuar nga';
$strFieldsTerminatedBy = 'Fushë e mbaruar nga';
$strFileCouldNotBeRead = 'File nuk mund të lexohet';
$strFileNameTemplate = 'Emri i file template';
$strFileNameTemplateHelp = 'Përdor __DB__ për emrin e databazës, __TABLE__ si emër për tabelën dhe %sany strftime%s opcione për specifikat e kohës, ekstensioni do të shtohet automatikisht. Çdo tekst tjetër do të konservohet.';
$strFileNameTemplateRemember = 'kujto template';
$strFixed = 'fiks';
$strFlushPrivilegesNote = 'Shënim: phpMyAdmin lexon privilegjet e përdoruesve direkt nga tabela e privilegjeve të MySQL. Përmbajtja e kësaj tabele mund të ndryshojë nga privilegjet e përdorur nga serveri nëse janë kryer ndryshime manuale. Në këtë rast, duhet të %srifreskoni privilegjet%s para se të vazhdoni.';
$strFlushTable = 'Rifillo ("FLUSH") tabelën';
$strFormEmpty = 'Mungon një vlerë në form!';
$strFormat = 'Formati';
$strFullText = 'Teksti i plotë';
$strFunction = 'Funksion';

$strGenBy = 'Gjeneruar nga';
$strGenTime = 'Gjeneruar më';
$strGeneralRelationFeat = 'Karakteristikat e përgjithshme të relacionit';
$strGlobal = 'globale';
$strGlobalPrivileges = 'Privilegje globale';
$strGlobalValue = 'Vlerë Globale';
$strGo = 'Zbato';
$strGrantOption = 'Grant';
$strGrants = 'Lejo';
$strGzip = '"kompresuar me gzip"';

$strHasBeenAltered = 'u modifikua.';
$strHasBeenCreated = 'u krijua.';
$strHaveToShow = 'Zgjidh të paktën një kolonë për ta vizualizuar';
$strHome = 'Home';
$strHomepageOfficial = 'Home page zyrtare e phpMyAdmin';
$strHomepageSourceforge = 'Home page e phpMyAdmin tek sourceforge.net';
$strHost = 'Host';
$strHostEmpty = 'Emri i host është bosh!';

$strId = 'ID';
$strIdxFulltext = 'Teksti komplet';
$strIfYouWish = 'Për të ngarkuar të dhënat vetëm për disa kollona të tabelës, specifiko listën e fushave (të ndara me presje).';
$strIgnore = 'Injoro';
$strIgnoringFile = 'File %s u injorua';
$strImportDocSQL = 'Importo files docSQL';
$strImportFiles = 'Importo files';
$strImportFinished = 'Importimi përfundoi';
$strInUse = 'në përdorim';
$strIndex = 'Treguesi';
$strIndexHasBeenDropped = 'Treguesi %s u eleminua';
$strIndexName = 'Emri i treguesit&nbsp;:';
$strIndexType = 'Tipi i treguesit&nbsp;:';
$strIndexes = 'Tregues';
$strInnodbStat = 'Gjëndja InnoDB';
$strInsecureMySQL = 'File i konfigurimit në përdorim përmban zgjedhje (root pa asnjë password) që korrispondojnë me të drejtat e account MySQL të paracaktuar. Një server MySQL funksionues me këto zgjedhje është i pambrojtur ndaj sulmeve, dhe ju duhet patjetër të korrigjoni këtë vrimë në siguri.';
$strInsert = 'Shto';
$strInsertAsNewRow = 'Shto një rresht të ri';
$strInsertNewRow = 'Shto një rresht të ri';
$strInsertTextfiles = 'Shto një file teksti në tabelë';
$strInsertedRows = 'Rreshta të shtuar:';
$strInstructions = 'Instruksione';
$strInvalidName = '"%s" është një fjalë e rezervuar; nuk mund ta përdorësh si emër për databazë/tabelë/fushë.';

$strJumpToDB = 'Kalo tek databaza &quot;%s&quot;.';
$strJustDelete = 'Vetëm fshi përdoruesit nga tabelat e privilegjeve.';
$strJustDeleteDescr = 'Përdoruesit e &quot;fshirë&quot; do të kenë akoma mundësi të futen në server si zakonisht derisa privilegjet të ngarkohen përsëri.';

$strKeepPass = 'Mos ndrysho password';
$strKeyname = 'Emri i kyçit';
$strKill = 'Hiq';

$strLaTeX = 'LaTeX';
$strLandscape = 'Horizontale';
$strLength = 'Gjatësia';
$strLengthSet = 'Gjatësia/Set*';
$strLimitNumRows = 'record për faqe';
$strLineFeed = 'Fundi i rreshtit: \\n';
$strLines = 'Record';
$strLinesTerminatedBy = 'Rreshta që mbarojnë me';
$strLinkNotFound = 'Link nuk u gjet';
$strLinksTo = 'Lidhje me';
$strLocalhost = 'Lokal';
$strLocationTextfile = 'Pozicioni i file';
$strLogPassword = 'Password:';
$strLogUsername = 'Emri i përdoruesit:';
$strLogin = 'Lidh';
$strLoginInformation = 'Informacione mbi Login';
$strLogout = 'Shkëput';

$strMIME_MIMEtype = 'Lloji MIME';
$strMIME_available_mime = 'Lloje MIME në dispozicion';
$strMIME_available_transform = 'Transformacionet në dispozicion';
$strMIME_description = 'Përshkrimi';
$strMIME_file = 'Emri i File';
$strMIME_nodescription = 'Asnjë përshkrim në dispozicion për këtë transformim.<br />Ju lutem pyet autorin, çfarë %s bën.';
$strMIME_transformation = 'Transformacioni i Browser';
$strMIME_transformation_note = 'Për listën e opcioneve të transformacioneve në dispozicion dhe transformacionet relativë të llojeve-MIME, kliko tek %stransformacione përshkrime%s';
$strMIME_transformation_options = 'Opcione të Transformimeve';
$strMIME_transformation_options_note = 'Ju lutem shkruani vlerat për opcionet e transformimit duke përdorur këtë format: \'a\',\'b\',\'c\'...<br />Nëse keni nevojë të shtoni një backslash ("\") apo një apostrofë ("\'") midis këtyre vlerave, duhet ti backslash-oni (për shembull \'\\\\xyz\' ose \'a\\\'b\').';
$strMIME_without = 'Llojet-MIME të print-uar në italics kanë një funksion transformacioni të veçantë';
$strMissingBracket = 'Mungojnë thonjëza';
$strModifications = 'Ndryshimet u shpëtuan';
$strModify = 'Modifiko';
$strModifyIndexTopic = 'Modifiko një tregues';
$strMoreStatusVars = 'Më shumë të ndryshueshme të gjendjes';
$strMoveTable = 'Sposto tabelën në (databazë<b>.</b>tabela):';
$strMoveTableOK = 'Tabela %s u spostua tek %s.';
$strMySQLCharset = 'Set karakteresh MySQL';
$strMySQLReloaded = 'MySQL u rifillua.';
$strMySQLSaid = 'Mesazh nga MySQL: ';
$strMySQLServerProcess = 'MySQL %pma_s1% në ekzekutim tek %pma_s2% si %pma_s3%';
$strMySQLShowProcess = 'Vizualizo proceset në ekzekutim';
$strMySQLShowStatus = 'Vizualizo informacionet e runtime të MySQL';
$strMySQLShowVars = 'Vizualizo të ndryshueshmet e sistemit të MySQL';

$strName = 'Emri';
$strNext = 'Në vazhdim';
$strNo = ' Jo ';
$strNoDatabases = 'Asnjë databazë';
$strNoDatabasesSelected = 'Asnjë databazë është zgjedhur.';
$strNoDescription = 'asnjë Përshkrim';
$strNoDropDatabases = 'Komandat "DROP DATABASE" janë disaktivuar.';
$strNoExplain = 'Mos Shpjego SQL';
$strNoFrames = 'phpMyAdmin funksionon më mirë me browser që suportojnë frames';
$strNoIndex = 'Asnjë tregues i përcaktuar!';
$strNoIndexPartsDefined = 'Asnjë pjesë e treguesit është përcaktuar!';
$strNoModification = 'Asnjë ndryshim';
$strNoOptions = 'Ky format nuk ka opcione';
$strNoPassword = 'Asnjë password';
$strNoPhp = 'pa kod PHP';
$strNoPrivileges = 'Asnjë privilegj';
$strNoQuery = 'Asnjë query SQL!';
$strNoRights = 'Nuk ke të drejta të mjaftueshme për të kryer këtë operacion!';
$strNoTablesFound = 'Nuk gjenden tabela në databazë.';
$strNoUsersFound = 'Nuk u gjet asnjë përdorues.';
$strNoUsersSelected = 'Nuk është seleksionuar asnjë përdorues.';
$strNoValidateSQL = 'Mos vleftëso SQL';
$strNone = 'Asnjë lloj';
$strNotNumber = 'Ky nuk është një numër!';
$strNotOK = 'jo OK';
$strNotSet = '<b>%s</b> tabela nuk u gjet ose nuk është përcaktuar tek %s';
$strNotValidNumber = ' nuk është një rresht i vlefshëm!';
$strNull = 'Null';
$strNumSearchResultsInTable = '%s korrispondon(jnë) tek tabela <i>%s</i>';
$strNumSearchResultsTotal = '<b>Gjithsej:</b> <i>%s</i> korrispondues(ë)';
$strNumTables = 'Tabela';

$strOK = 'OK';
$strOftenQuotation = 'Zakonisht nga dopjo thonjza. ME DËSHIRË tregon që vetëm fushat <I>char</I> dhe <I>varchar</I> duhet të delimitohen nga karakteri i treguar.';
$strOperations = 'Operacione';
$strOptimizeTable = 'Optimizo tabelën';
$strOptionalControls = 'Me dëshirë. Ky karakter kontrollon si të shkruash apo lexosh karakteret specialë.';
$strOptionally = 'ME DËSHIRË';
$strOptions = 'Mundësi';
$strOr = 'Ose';
$strOverhead = 'Mbi limit';

$strPHP40203 = 'Është në përdorim PHP 4.2.3, që përmban një bug serioz me stringat multi-byte strings (mbstring). Shiko raportin 19404 të bug PHP. Ky version i PHP nuk rekomandohet për tu përdorur me phpMyAdmin.';
$strPHPVersion = 'Versioni i PHP';
$strPageNumber = 'Numri i faqes:';
$strPartialText = 'Tekst i pjesëshëm';
$strPassword = 'Password';
$strPasswordChanged = 'Password për përdoruesin %s u ndryshua me sukses.';
$strPasswordEmpty = 'Password është bosh!';
$strPasswordNotSame = 'Password nuk korrispondon!';
$strPdfDbSchema = 'Skema e databazë "%s" - Faqja %s';
$strPdfInvalidPageNum = 'Numri i faqes së PDF i papërcaktuar!';
$strPdfInvalidTblName = 'Tabela "%s" nuk ekziston!';
$strPdfNoTables = 'Asnjë tabelë';
$strPerHour = 'në orë';
$strPerMinute = 'në minutë';
$strPerSecond = 'në sekondë';
$strPhp = 'Krijo kodin PHP';
$strPmaDocumentation = 'Dokumente të phpMyAdmin';
$strPmaUriError = 'Direktiva <tt>$cfg[\'PmaAbsoluteUri\']</tt> DUHET të përcaktohet tek file i konfigurimit!';
$strPortrait = 'Vertikal';
$strPos1 = 'Fillim';
$strPrevious = 'Paraardhësi';
$strPrimary = 'Primar';
$strPrimaryKey = 'Kyç primar';
$strPrimaryKeyHasBeenDropped = 'Kyçi primar u eleminua';
$strPrimaryKeyName = 'Emri i kyçit primar duhet të jetë... PRIMARY!';
$strPrimaryKeyWarning = '("PRIMARY" <b>duhet</b> të jetë emri i, dhe <b>vetëm i</b>, një kyçi primar!)';
$strPrint = 'Printo';
$strPrintView = 'Vizualizo për printim';
$strPrivDescAllPrivileges = 'Përfshin të gjitha të drejtat me përjashtim të GRANT.';
$strPrivDescAlter = 'Lejon ndryshimin e strukturës së tabelave ekzistuese.';
$strPrivDescCreateDb = 'Lejon krijimin e tabelave të reja dhe databazave të reja.';
$strPrivDescCreateTbl = 'Lejon krijimin e tabelave të reja.';
$strPrivDescCreateTmpTable = 'Lejon krijimin e tabelave të përkohëshme.';
$strPrivDescDelete = 'Lejon fshirjen e të dhënave.';
$strPrivDescDropDb = 'Lejon eleminimin e databazave dhe tabelave.';
$strPrivDescDropTbl = 'Lejon eleminimin e tabelave.';
$strPrivDescExecute = 'Lejon ekzekutimin e procedurave të regjistruara; Nuk ka efekt në këtë version të MySQL.';
$strPrivDescFile = 'Lejon importimin e të dhënave nga dhe eksportimin e të dhënave në files.';
$strPrivDescGrant = 'Lejon të shtosh përdorues dhe privilegje pa ringarkuar tabelat e privilegjeve.';
$strPrivDescIndex = 'Lejon krijimin dhe eleminimin e treguesve.';
$strPrivDescInsert = 'Lejon futjen dhe mbishkrimin e të dhënave.';
$strPrivDescLockTables = 'Lejon bllokimin e tabelave për thread e momentit.';
$strPrivDescMaxConnections = 'Kufizon numrin e lidhjeve të reja që një përdorues mund të hapë në një orë.';
$strPrivDescMaxQuestions = 'Kufizon numrin e kërkesave që një përdorues mund ti dërgojë server-it në një orë.';
$strPrivDescMaxUpdates = 'Kufizon numrin e komandave që mund të ndryshojnë një tabelë apo databazë që një përdorues mund të zbatojë në një orë.';
$strPrivDescProcess3 = 'Lejon vrasjen e proceseve të përdoruesve të tjerë.';
$strPrivDescProcess4 = 'Lejon shikimin e queries komplete tek lista e proceseve.';
$strPrivDescReferences = 'Nuk ka asnjë efekt tek ky version i MySQL.';
$strPrivDescReload = 'Lejon ringarkimin e parametrave të server-it dhe risetimin e cache të server/ve.';
$strPrivDescReplClient = 'I jep të drejtën një përdoruesi të pyesë se ku gjenden slaves / masters.';
$strPrivDescReplSlave = 'Nevoitet për replikimin e slaves.';
$strPrivDescSelect = 'Lejon leximin e të dhënave.';
$strPrivDescShowDb = 'Mundëson hyrjen tek lista komplete e databazave.';
$strPrivDescShutdown = 'Lejon fikjen e server-it.';
$strPrivDescSuper = 'Lejon lidhje të tjera, edhe po të jetë arritur numri maksimal i lidhjeve; I nevojshëm për shumë operacione administrimi si përcaktimi i të ndryshueshmeve globale apo fshirja e threads të përdoruesve të tjerë.';
$strPrivDescUpdate = 'Lejon ndryshimin e të dhënave.';
$strPrivDescUsage = 'Asnjë privilegj.';
$strPrivileges = 'Privilegje';
$strPrivilegesReloaded = 'Privilegjet u ringarkuan me sukses.';
$strProcesslist = 'Lista e Proceseve';
$strProperties = 'Pronësi';
$strPutColNames = 'Vendos emrat e kollonave tek rreshti i parë';

$strQBE = 'Query nga shembull';
$strQBEDel = 'Fshi';
$strQBEIns = 'Shto';
$strQueryFrame = 'Dritarja e Query';
$strQueryFrameDebug = 'Informacione të Debug';
$strQueryFrameDebugBox = 'Të ndryshueshme aktive për formularin e query:\nDB: %s\nTabella: %s\nServer-i: %s\n\nTë ndryshueshmet aktuale për formularin e query:\nDB: %s\nTabella: %s\nServer-i: %s\n\nPozicioni i Opener: %s\nPozicioni i Frameset: %s.';
$strQueryOnDb = 'SQL-query tek databazë <b>%s</b>:';
$strQuerySQLHistory = 'Kronollogjia e SQL';
$strQueryStatistics = '<b>Statistikat e Query</b>: Që nga nisja e tij, server-it i janë dërguar %s queries.';
$strQueryTime = 'Query ka zgjatur %01.4f sec';
$strQueryType = 'Lloji i query';

$strReType = 'Rifut';
$strReceived = 'Marrë';
$strRecords = 'Record';
$strReferentialIntegrity = 'Kontrollo integritetin e informacioneve:';
$strRelationNotWorking = 'Karakteristikat shtesë janë disaktivuar për sa i takon funksionimit me tabelat e link-uara. Për të zbuluar përse, klikoni %skëtu%s.';
$strRelationView = 'Shiko relacionin';
$strRelationalSchema = 'Skema relazionale';
$strReloadFailed = 'Rinisja e MySQL dështoi.';
$strReloadMySQL = 'Rinis MySQL';
$strReloadingThePrivileges = 'Duke rilexuar privilegjet';
$strRememberReload = 'Kujtohu të rinisësh MySQL.';
$strRemoveSelectedUsers = 'Heq përdoruesit e zgjedhur';
$strRenameTable = 'Riemërto tabelën në';
$strRenameTableOK = 'Tabela %s u riemërtua %s';
$strRepairTable = 'Riparo tabelën';
$strReplace = 'Zëvëndëso';
$strReplaceTable = 'Zëvëndëso të dhënat e tabelës me file';
$strReset = 'Rinis';
$strResourceLimits = 'Limitet e rezervave';
$strRevoke = 'Hiq';
$strRevokeAndDelete = 'Hiqja të gjitha të drejtat aktive përdoruesve dhe pastaj eleminoi.';
$strRevokeAndDeleteDescr = 'Përdoruesit DO TË MUND TË PËRDORIN sidoqoftë privilegjin deri atëhere kur privilegjet të ringarkohen.';
$strRevokeGrant = 'Hiq të drejtat';
$strRevokeGrantMessage = 'Ke hequr privilegjet e të drejtave për %s';
$strRevokeMessage = 'Ke anulluar privilegjet për %s';
$strRevokePriv = 'Anullo privilegjet';
$strRowLength = 'Gjatësia e rreshtit';
$strRowSize = 'Dimensioni i rreshtit';
$strRows = 'rreshta';
$strRowsFrom = 'rreshta duke filluar nga';
$strRowsModeFlippedHorizontal = 'horizontal (headers të rrotulluar)';
$strRowsModeHorizontal = ' horizontal ';
$strRowsModeOptions = ' në modalitetin %s dhe përsërit headers mbas %s qeli ';
$strRowsModeVertical = ' vertikal ';
$strRowsStatistic = 'Statistikat e rreshtave';
$strRunQuery = 'Dërgo Query';
$strRunSQLQuery = 'Zbato query SQL tek databazë %s';
$strRunning = 'në ekzekutim tek %s';

$strSQL = 'SQL';
$strSQLOptions = 'Opcione SQL';
$strSQLParserBugMessage = 'Ka mundësi që ka një bug tek parser SQL. Ju lutem, kontrolloni query tuaj me kujdes, dhe kontrolloni që presjet të jenë ku duhet dhe jo të gabuara. Një shkak tjetër i mundshëm i gabimit mund të jetë që po mundoheni të uploadoni një file binar jashtë një zone teksti të kufizuar me presje. Mund edhe të provoni query tuaj MySQL nga interfaqja e shkruar e komandave. Gabimi i mëposhtëm i kthyer nga server-i MySQL, nëse ekziston një i tillë, mund tju ndihmojë në diagnostikimin e problemit. Nëse ka akoma probleme, apo n.q.s. parser-i SQL i phpMyAdmin gabon kur përkundrazi nga interfaqja e komandave të thjeshta nuk rezultojnë probleme, ju lutem zvogëloni query tuaj SQL në hyrje në query e vetme që shkakton probleme, dhe dërgoni një bug raportim me të dhënat rezultuese nga seksioni CUT i mëposhtëm:';
$strSQLParserUserError = 'Mesa duket ekziston një gabim tek query juaj SQL e futur. Gabimi i server-it MySQL i treguar më poshtë, nëse ekziston, mund t\'ju ndihmojë në diagnostikimin e problemit';
$strSQLQuery = 'query SQL';
$strSQLResult = 'Rezultati SQL';
$strSQPBugInvalidIdentifer = 'Identifikues i pavlefshëm';
$strSQPBugUnclosedQuote = 'Thonjëza të pambyllura';
$strSQPBugUnknownPunctuation = 'Stringë Punctuation e panjohur';
$strSave = 'Shpëto';
$strScaleFactorSmall = 'Faktori i shkallës është shumë i vogël për të plotësuar skemën në faqe';
$strSearch = 'Kërko';
$strSearchFormTitle = 'Kërko në databazë';
$strSearchInTables = 'Tek tabela(at):';
$strSearchNeedle = 'Fjala(ë) apo vlera(at) për t\'u kërkuar (karakteri Jolly: "%"):';
$strSearchOption1 = 'të paktën njërën nga fjalët';
$strSearchOption2 = 'të gjitha fjalët';
$strSearchOption3 = 'fraza precize';
$strSearchOption4 = 'si ekspresion i rregullt';
$strSearchResultsFor = 'Kërko rezultatet për "<i>%s</i>" %s:';
$strSearchType = 'Gjej:';
$strSelect = 'Seleksiono';
$strSelectADb = 'Të lutem, seleksiono një databazë';
$strSelectAll = 'Seleksiono Gjithçka';
$strSelectFields = 'Seleksiono fushat (të paktën një):';
$strSelectNumRows = 'tek query';
$strSelectTables = 'Seleksiono Tabelat';
$strSend = 'Shpëtoje me emër...';
$strSent = 'Dërguar';
$strServer = 'Server %s';
$strServerChoice = 'Zgjedhja e server';
$strServerStatus = 'Informacione mbi Runtime';
$strServerStatusUptime = 'Ky server server MySQL po punon që prej %s. U nis më %s.';
$strServerTabProcesslist = 'Proceset';
$strServerTabVariables = 'Të ndryshueshmet';
$strServerTrafficNotes = '<b>Trafiku i server-it</b>: Këto tabela do të shfaqin statistikat e trafikut të rrjetit për këtë server MySQL që nga momenti i nisjes së tij.';
$strServerVars = 'Të ndryshueshmet dhe parametrat e Server-it';
$strServerVersion = 'Versioni i MySQL';
$strSessionValue = 'Vlera seancës';
$strSetEnumVal = 'N.q.s. fusha është "enum" apo "set", shtoni të dhënat duke përdorur formatin: \'a\',\'b\',\'c\'...<br />Nëse megjithatë do t\'u duhet të vini backslashes ("\") apo single quote ("\'") para këtyre vlerave, backslash-ojini (për shembull \'\\\\xyz\' o \'a\\\'b\').';
$strShow = 'Shfaq';
$strShowAll = 'Shfaqi të gjithë';
$strShowColor = 'Shfaq ngjyrën';
$strShowCols = 'Shfaq kollonat';
$strShowDatadictAs = 'Formati i Data Dictionary';
$strShowFullQueries = 'Shfaq Queries komplete';
$strShowGrid = 'Shfaq rrjetën';
$strShowPHPInfo = 'Trego info mbi PHP';
$strShowTableDimension = 'Trego dimensionin e tabelave';
$strShowTables = 'Shfaq tabelat';
$strShowThisQuery = 'Tregoje përsëri këtë query';
$strShowingRecords = 'Vizualizimi i record ';
$strSingly = '(një nga një)';
$strSize = 'Dimensioni';
$strSort = 'rreshtimi';
$strSpaceUsage = 'Hapësira e përdorur';
$strSplitWordsWithSpace = 'Fjalët janë të ndara nga një hapsirë (" ").';
$strStatCheckTime = 'Kontrolli i fundit';
$strStatCreateTime = 'Krijimi';
$strStatUpdateTime = 'Ndryshimi i fundit';
$strStatement = 'Instruksione';
$strStatus = 'Gjendja';
$strStrucCSV = 'të dhëna CSV';
$strStrucData = 'Struktura dhe të dhëna';
$strStrucDrop = 'Shto \'drop table\'';
$strStrucExcelCSV = 'CSV për të dhëna Ms Excel';
$strStrucOnly = 'Vetëm struktura';
$strStructPropose = 'Propozo strukturën e tabelës';
$strStructure = 'Struktura';
$strSubmit = 'Dërgoje';
$strSuccess = 'Query u zbatua me sukses';
$strSum = 'Gjithsej';
$strSwitchToTable = 'Kalo tek tabela e kopjuar';

$strTable = 'Tabela';
$strTableComments = 'Komente mbi tabelën';
$strTableEmpty = 'Emri i tabelës është bosh!';
$strTableHasBeenDropped = 'Tabela %s u eleminua';
$strTableHasBeenEmptied = 'Tabela %s u zbraz';
$strTableHasBeenFlushed = 'Tabela %s u rifreskua';
$strTableMaintenance = 'Administrimi i tabelës';
$strTableOfContents = 'Tabela e përmbajtjes';
$strTableStructure = 'Struktura e tabelës';
$strTableType = 'Tipi i tabelës';
$strTables = '%s tabela(at)';
$strTblPrivileges = 'Të drejta relative me tabelat';
$strTextAreaLength = ' Për shkak të gjatësisë saj,<br /> kjo fushë nuk mund të modifikohet ';
$strTheContent = 'Përmbajtja e file u shtua.';
$strTheContents = 'Përmbajtja e file zëvëndëson rreshtat e tabelës me të njëjtin kyç primar apo kyç të vetëm.';
$strTheTerminator = 'Karakteri përfundues i fushave.';
$strThisHost = 'Këtë Host';
$strThisNotDirectory = 'Kjo nuk është një directory';
$strThreadSuccessfullyKilled = 'Thread %s u përfundua me sukses.';
$strTime = 'Kohë';
$strTotal = 'Gjithsej';
$strTotalUC = 'Gjithsej';
$strTraffic = 'Trafiku';
$strTransformation_image_jpeg__inline = 'Vizualizon një thumbnail të klikueshëm; mundësitë: gjërësia, lartësia në pixels (ruan proporcionin origjinal)';
$strTransformation_image_jpeg__link = 'Shfaq një link për tek kjo figurë (download blob direkt, p.sh.).';
$strTransformation_image_png__inline = 'Shiko figurën/jpeg: në line';
$strTransformation_text_plain__dateformat = 'Merr një fushë TIME, TIMESTAMP apo DATETIME dhe e formaton duke përdorur formatin e datës lokale. Mundësia e parë është offset (në orë) që do ti shtohet timestamp (Default: 0). Mundësia e dytë është një format date në përputhje me parametrat në dispozicion për funksionin strftime() të PHP.';
$strTransformation_text_plain__external = 'VETËM PËR LINUX: Lëshon një program të jashtëm dhe plotëson të dhënat e fushave me anë të standard input. Jep si rezultat standard output e programit. Vendosja default është Tidy, për të printuar si duhet kodin HTML. Për arsye sigurie, do t\'ju duhet të ndryshoni file libraries/transformations/text_plain__external.inc.php dhe të shkruani instrumentet që lejoni të përdoren. Mundësia e parë pra është numri i programit që dëshironi të përdorni dhe e dyta janë parametrat për programin. Parametri i tretë, n.q.s. i vendosur në 1 do të konvertojë output duke përdorur htmlspecialchars() (Prezgjedhur: 1). Një parametër i katërt, po të jetë vendosur në 1 do t\'i shtojë një NOWRAP përmbajtjes së qelisë kështu që output komplet do të shfaqet pa u riformatuar (Default: 1)';
$strTransformation_text_plain__formatted = 'Konservon formatimin origjinal të fushës. Nuk aplikohet asnjë Escaping.';
$strTransformation_text_plain__imagelink = 'Shfaq një figurë dhe një link, fusha përmban emrin e file; opcioni i parë është një prefiks si "http://domain.com/", opcioni i dytë është gjërësia në pixels, i treti është lartësia.';
$strTransformation_text_plain__link = 'Shfaq një link, fusha përmban emrin e file; opcioni i parë është një prefiks si "http://domain.com/", opcioni i dytë është një titull për lidhjen.';
$strTransformation_text_plain__substr = 'Shfaq vetëm një pjesë të string-ës. Opcioni i parë është offset-i që shërben për të përcaktuar ku fillon output i tekstit tuaj (Default 0). Opcioni i dytë është një offset që tregon se sa tekst kthehet. Po të jetë bosh, kthen të gjithë tekstin e mbetur. Opcioni i trtë përcakton çfarë karakteresh do të shtohen në fund të output kur kthehet një nën-string-ë (Default: ...) .';
$strTransformation_text_plain__unformatted = 'Shfaq kodin HTML si entitet HTML. Formatimi HTML nuk shfaqet.';
$strTruncateQueries = 'Shkurton (ndërpret) Queries e Shfaqura';
$strType = 'Lloji';

$strUncheckAll = 'Deseleksionoi të gjithë';
$strUnique = 'I vetëm';
$strUnselectAll = 'Deseleksiono gjithçka';
$strUpdComTab = 'Ju lutem lexoni dokumentet mbi rifreskimin e tabelës suaj Column_comments';
$strUpdatePrivMessage = 'Ke rifreskuar lejet për %s.';
$strUpdateProfile = 'Rifresko profilin:';
$strUpdateProfileMessage = 'Profili u rifreskua.';
$strUpdateQuery = 'Rifresko Query';
$strUsage = 'Përdorimi';
$strUseBackquotes = 'Përdor backquotes me emrat e tabelave dhe fushave';
$strUseHostTable = 'Përdor Tabelën e Host-it';
$strUseTables = 'Përdor tabelat';
$strUseTextField = 'Përdor fushë teksti';
$strUser = 'Përdorues';
$strUserAlreadyExists = 'Përdoruesi %s ekziston!';
$strUserEmpty = 'Emri i përdoruesit është bosh!';
$strUserName = 'Emri i përdoruesit';
$strUserNotFound = 'Përdoruesi i seleksionuar nuk u gjet tek tabela e privilegjeve.';
$strUserOverview = 'Pamja e përgjithshme e përdoruesit';
$strUsers = 'Përdorues';
$strUsersDeleted = 'Përdoruesit e zgjedhur u hoqën me sukses.';
$strUsersHavingAccessToDb = 'Përdoruesit që kanë hyrje tek &quot;%s&quot;';

$strValidateSQL = 'Vleftëso SQL';
$strValidatorError = 'Miratuesi SQL nuk arrin të niset. Ju lutem kontrolloni instalimin e ekstensioneve të duhura php ashtu si përshkruhet tek %sdokumentimi%s.';
$strValue = 'Vlerë';
$strVar = 'E ndryshueshme';
$strViewDump = 'Vizualizo dump (skema) e tabelës';
$strViewDumpDB = 'Vizualizo dump (skema) e databazë';

$strWebServerUploadDirectory = 'directory e upload të server-it web';
$strWebServerUploadDirectoryError = 'Directory që keni zgjedhur për upload nuk arrin të gjehet';
$strWelcome = 'Mirësevini tek %s';
$strWildcard = 'wildcard';
$strWithChecked = 'N.q.s. të seleksionuar:';
$strWritingCommentNotPossible = 'I pamundur shkrimi i komentit';
$strWritingRelationNotPossible = 'I pamundur shkrimi i Relacionit';
$strWrongUser = 'Emri i përdoruesit apo password i gabuar. Ndalohet hyrja.';

$strXML = 'XML';

$strYes = ' Po ';

$strZeroRemovesTheLimit = 'Shënim: Vendosja e këtyre opcioneve në 0 (zero) sinjifikon asnjë limit.';
$strZip = '"kompresuar me zip"';
// To translate

$strCharset = 'Charset';  //to translate
$strLaTeXOptions = 'LaTeX options';  //to translate
$strRelations = 'Relations';  //to translate
$strMoveTableSameNames = 'Can\'t move table to same one!';  //to translate
$strCopyTableSameNames = 'Can\'t copy table to same one!';  //to translate
$strMustSelectFile = 'You should select file which you want to insert.';  //to translate
$strSaveOnServer = 'Save on server in %s directory';  //to translate
$strOverwriteExisting = 'Overwrite existing file(s)';  //to translate
$strFileAlreadyExists = 'File %s already exists on server, change filename or check overwrite option.';  //to translate
$strDumpSaved = 'Dump has been saved to file %s.';  //to translate
$strNoPermission = 'The web server does not have permission to save the file %s.';  //to translate
$strNoSpace = 'Insufficient space to save the file %s.';  //to translate
$strInsertedRowId = 'Inserted row id:';  //to translate
$strLoadMethod = 'LOAD method';  //to translate
$strLoadExplanation = 'The best method is checked by default, but you can change if it fails.';  //to translate
$strExecuteBookmarked = 'Execute bookmarked query';  //to translate
$strExcelOptions = 'Excel options';  //to translate
$strReplaceNULLBy = 'Replace NULL by';  //to translate
$strQueryWindowLock = 'Do not overwrite this query from outside the window';  //to translate
$strPaperSize = 'Paper size';  //to translate
$strDatabaseNoTable = 'This database contains no table!';//to translate
$strViewDumpDatabases = 'View dump (schema) of databases';//to translate
$strAddIntoComments = 'Add into comments';//to translate
$strDatabaseExportOptions = 'Database export options';//to translate
$strAddDropDatabase = 'Add DROP DATABASE';//to translate
$strToggleScratchboard = 'toggle scratchboard';  //to translate
$strTableOptions = 'Table options';  //to translate
$strSecretRequired = 'The configuration file now needs a secret passphrase (blowfish_secret).';  //to translate
$strAccessDeniedExplanation = 'phpMyAdmin tried to connect to the MySQL server, and the server rejected the connection. You should check the host, username and password in config.inc.php and make sure that they correspond to the information given by the administrator of the MySQL server.';  //to translate
$strAddAutoIncrement = 'Add AUTO_INCREMENT value';  //to translate
$strCharsets = 'Charsets';  //to translate
$strDescription = 'Description';  //to translate
$strCharsetsAndCollations = 'Character Sets and Collations';  //to translate
$strCollation = 'Collation';  //to translate
$strMultilingual = 'multilingual';  //to translate
$strGerman = 'German';  //to translate
$strPhoneBook = 'phone book';  //to translate
$strDictionary = 'dictionary';  //to translate
$strSwedish = 'Swedish';  //to translate
$strDanish = 'Danish';  //to translate
$strCzech = 'Czech';  //to translate
$strTurkish = 'Turkish';  //to translate
$strEnglish = 'English';  //to translate
$strHungarian = 'Hungarian';  //to translate
$strCroatian = 'Croatian';  //to translate
$strBulgarian = 'Bulgarian';  //to translate
$strLithuanian = 'Lithuanian';  //to translate
$strEstonian = 'Estonian';  //to translate
$strCaseInsensitive = 'case-insensitive';  //to translate
$strCaseSensitive = 'case-sensitive';  //to translate
$strUkrainian = 'Ukrainian';  //to translate
$strHebrew = 'Hebrew';  //to translate
$strWestEuropean = 'West European';  //to translate
$strCentralEuropean = 'Central European';  //to translate
$strTraditionalChinese = 'Traditional Chinese';  //to translate
$strCyrillic = 'Cyrillic';  //to translate
$strArmenian = 'Armenian';  //to translate
$strArabic = 'Arabic';  //to translate
$strRussian = 'Russian';  //to translate
$strUnknown = 'unknown';  //to translate
$strBaltic = 'Baltic';  //to translate
$strUnicode = 'Unicode';  //to translate
$strSimplifiedChinese = 'Simplified Chinese';  //to translate
$strKorean = 'Korean';  //to translate
$strGreek = 'Greek';  //to translate
$strJapanese = 'Japanese';  //to translate
$strThai = 'Thai';  //to translate
$strUseThisValue = 'Use this value';  //to translate
$strWindowNotFound = 'The target browser window could not be updated. Maybe you have closed the parent window or your browser is blocking cross-window updates of your security settings';  //to translate
$strBrowseForeignValues = 'Browse foreign values';  //to translate
?>
