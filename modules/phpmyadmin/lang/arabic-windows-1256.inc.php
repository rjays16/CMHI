<?php
/* $Id: arabic-windows-1256.inc.php,v 1.2 2005/10/29 20:08:11 kaloyan_raev Exp $ */

/**
 * Original translation to Arabic by Fisal <fisal77 at hotmail.com>
 * Update by Tarik kallida <kallida at caramail.com>
 * Final Update on Februray 4, 2002 by Ossama Khayat <ossamak at nht.com.kw>
 */

$charset = 'windows-1256';
$text_dir = 'rtl'; // ('ltr' for left to right, 'rtl' for right to left)
$left_font_family = 'Tahoma, verdana, arial, helvetica, sans-serif';
$right_font_family = '"Windows UI", Tahoma, verdana, arial, helvetica, sans-serif';
$number_thousands_separator = ',';
$number_decimal_separator = '.';
// shortcuts for Byte, Kilo, Mega, Giga, Tera, Peta, Exa
//$byteUnits = array('����', '��������', '��������', '��������');
$byteUnits = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

$day_of_week = array('�����', '�������', '��������', '��������', '������', '������', '�����');
$month = array('�����', '������', '����', '�����', '����', '�����', '�����', '�����', '������', '������', '������', '������');
// See http://www.php.net/manual/en/function.strftime.php to define the
// variable below
$datefmt = '%d %B %Y ������ %H:%M';

$strAccessDenied = '��� �����';
$strAction = '�������';
$strAddDeleteColumn = '�����/��� ���� ���';
$strAddDeleteRow = '�����/��� �� ���';
$strAddNewField = '����� ��� ����';
$strAddPriv = '����� ������ ����';
$strAddPrivMessage = '��� ���� ������ ����.';
$strAddSearchConditions = '��� ���� ����� (��� �� ������ "where" clause):';
$strAddToIndex = '����� ����� &nbsp;%s&nbsp;��(���)';
$strAddUser = '��� ������ ����';
$strAddUserMessage = '��� ���� ������ ����.';
$strAffectedRows = '���� �����:';
$strAfter = '��� %s';
$strAfterInsertBack = '������ ��� ������ �������';
$strAfterInsertNewInsert = '����� ����� ����';
$strAll = '����';
$strAlterOrderBy = '����� ����� ������ ��';
$strAnalyzeTable = '����� ������';
$strAnd = '�';
$strAnIndex = '��� ����� ������ �� %s';
$strAny = '��';
$strAnyColumn = '�� ����';
$strAnyDatabase = '�� ����� ������';
$strAnyHost = '�� ����';
$strAnyTable = '�� ����';
$strAnyUser = '�� ������';
$strAPrimaryKey = '��� ����� ������� ������� �� %s';
$strAscending = '��������';
$strAtBeginningOfTable = '�� ����� ������';
$strAtEndOfTable = '�� ����� ������';
$strAttr = '������';

$strBack = '����';
$strBinary = '�����';
$strBinaryDoNotEdit = '����� - �������';
$strBookmarkDeleted = '��� ����� ������� ��������.';
$strBookmarkLabel = '�����';
$strBookmarkQuery = '����� ������ SQL-�������';
$strBookmarkThis = '���� ����� ������ SQL-�������';
$strBookmarkView = '��� ���';
$strBrowse = '�������';
$strBzip = '"bzipped"';

$strCantLoadMySQL = '������ ����� ������ MySQL,<br />������ ��� ������� PHP.';
$strCantRenameIdxToPrimary = '������ ����� ��� ������ ��� �������!';
$strCardinality = 'Cardinality';
$strCarriage = '����� �������: \\r';
$strChange = '�����';
$strChangePassword = '����� ���� ����';
$strCheckAll = '���� ����';
$strCheckDbPriv = '��� ������ ����� ��������';
$strCheckTable = '������ �� ������';
$strColumn = '����';
$strColumnNames = '��� ������';
$strCompleteInserts = '������� ��� �����';
$strConfirm = '�� ���� ���� �� ���� ��߿';
$strCookiesRequired = '��� ����� ��� ������� �� ��� �������.';
$strCopyTable = '��� ������ ���';
$strCopyTableOK = '������ %s ��� �� ���� ��� %s.';
$strCreate = '�����';
$strCreateIndex = '����� ����� ���&nbsp;%s&nbsp;����';
$strCreateIndexTopic = '����� ����� �����';
$strCreateNewDatabase = '����� ����� ������ �����';
$strCreateNewTable = '����� ���� ���� �� ����� �������� %s';
$strCriteria = '��������';

$strData = '������';
$strDatabase = '����� �������� ';
$strDatabaseHasBeenDropped = '����� ������ %s ������.';
$strDatabases = '����� ������';
$strDatabasesStats = '�������� ����� ��������';
$strDatabaseWildcard = '����� ������:';
$strDataOnly = '������ ���';
$strDefault = '�������';
$strDelete = '���';
$strDeleted = '��� �� ��� ����';
$strDeletedRows = '������ ��������:';
$strDeleteFailed = '����� ����!';
$strDeleteUserMessage = '��� ���� �������� %s.';
$strDescending = '��������';
$strDisplay = '���';
$strDisplayOrder = '����� �����:';
$strDoAQuery = '���� "������� ������ ������" (wildcard: "%")';
$strDocu = '������� �������';
$strDoYouReally = '�� ���� ���� �����';
$strDrop = '���';
$strDropDB = '��� ����� ������ %s';
$strDropTable = '��� ����';
$strDumpingData = '����� �� ������� ������ ������';
$strDynamic = '��������';

$strEdit = '�����';
$strEditPrivileges = '����� ����������';
$strEffective = '����';
$strEmpty = '����� �����';
$strEmptyResultSet = 'MySQL ��� ������ ����� ����� ����� (�����. �� ����).';
$strEnd = '�����';
$strEnglishPrivileges = ' ������: ��� �������� ��MySQL ���� ������ ������ ���������� ��� ';
$strError = '���';
$strExtendedInserts = '����� ����';
$strExtra = '�����';

$strField = '�����';
$strFieldHasBeenDropped = '��� ����� %s';
$strFields = ' ��� ������';
$strFieldsEmpty = ' ����� ����� ����! ';
$strFieldsEnclosedBy = '��� ���� ��';
$strFieldsEscapedBy = '��� ������� ��';
$strFieldsTerminatedBy = '��� ����� ��';
$strFixed = '����';
$strFlushTable = '����� ����� ������ ("FLUSH")';
$strFormat = '����';
$strFormEmpty = '���� ���� ������ �������� !';
$strFullText = '���� �����';
$strFunction = '����';

$strGenTime = '���� ��';
$strGo = '&nbsp;�������&nbsp;';
$strGrants = 'Grants';
$strGzip = '"gzipped"';

$strHasBeenAltered = '��� �����.';
$strHasBeenCreated = '��� ����.';
$strHome = '������ ��������';
$strHomepageOfficial = '������ �������� ������� �� phpMyAdmin';
$strHomepageSourceforge = 'Sourceforge phpMyAdmin ���� �������';
$strHost = '������';
$strHostEmpty = '��� �������� ����!';

$strIdxFulltext = '���� ������';
$strIfYouWish = '��� ��� ���� �� �� ���� ��� ����� ������ ���, ��� �������� ���� ���� ����� �����.';
$strIgnore = '�����';
$strIndex = '�����';
$strIndexes = '�����';
$strIndexHasBeenDropped = '����� ������ %s';
$strIndexName = '��� ������&nbsp;:';
$strIndexType = '��� ������&nbsp;:';
$strInsert = '�����';
$strInsertAsNewRow = '����� ������ ����';
$strInsertedRows = '���� �����:';
$strInsertNewRow = '����� ����� ����';
$strInsertTextfiles = '����� ��� ��� �� ������';
$strInstructions = '�������';
$strInUse = '��� ���������';
$strInvalidName = '"%s" ���� ������, ������� ��������� ���� ����� ������/����/���.';

$strKeepPass = '������ ���� ����';
$strKeyname = '��� �������';
$strKill = '�����';

$strLength = '�����';
$strLengthSet = '�����/������*';
$strLimitNumRows = '��� ������� ��� ����';
$strLineFeed = '���� �����: \\n';
$strLines = '����';
$strLinesTerminatedBy = '���� ������ ��';
$strLocationTextfile = '���� ��� ���';
$strLogin = '����';
$strLogout = '����� ����';
$strLogPassword = '���� ����:';
$strLogUsername = '��� ���������:';

$strModifications = '��� ���������';
$strModify = '�����';
$strModifyIndexTopic = '����� �������';
$strMoveTable = '��� ���� ��� (����� ������<b>.</b>����):';
$strMoveTableOK = '%s ���� �� ���� ��� %s.';
$strMySQLReloaded = '�� ����� ����� MySQL �����.';
$strMySQLSaid = 'MySQL ���: ';
$strMySQLServerProcess = 'MySQL %pma_s1%  ��� ������ %pma_s2% -  �������� : %pma_s3%';
$strMySQLShowProcess = '��� ��������';
$strMySQLShowStatus = '��� ���� ������ MySQL';
$strMySQLShowVars ='��� ������� ������ MySQL';

$strName = '�����';
$strNext = '������';
$strNo = '��';
$strNoDatabases = '������ ����� ������';
$strNoDropDatabases = '���� "��� ����� ������"����� ';
$strNoFrames = 'phpMyAdmin ���� ������ �� ������ <b>��������</b>.';
$strNoIndex = '���� ��� ����!';
$strNoIndexPartsDefined = '����� ������� ��� �����!';
$strNoModification = '�� �������';
$strNone = '����';
$strNoPassword = '�� ���� ��';
$strNoPrivileges = '������ ��� �����';
$strNoQuery = '���� ������� SQL!';
$strNoRights = '��� ���� ������ ������� ��� ���� ��� ����!';
$strNoTablesFound = '������ ����� ������ �� ����� �������� ���!.';
$strNotNumber = '��� ��� ���!';
$strNotValidNumber = ' ��� ��� ��� �� ����!';
$strNoUsersFound = '��������(���) �� ��� �������.';
$strNull = '����';

$strOftenQuotation = '������ ������ ��������. ������� ���� ��� ������  char � varchar ���� �� " ".';
$strOptimizeTable = '��� ������';
$strOptionalControls = '�������. ������ �� ����� ����� �� ����� ������ �� ����� ������.';
$strOptionally = '�������';
$strOr = '��';
$strOverhead = '������';

$strPartialText = '���� �����';
$strPassword = '���� ����';
$strPasswordEmpty = '���� ���� ����� !';
$strPasswordNotSame = '����� ���� ��� ��������� !';
$strPHPVersion = ' PHP ������';
$strPmaDocumentation = '������� ������� �� phpMyAdmin (�����������)';
$strPmaUriError = '������� <span dir="ltr"><tt>$cfg[\'PmaAbsoluteUri\']</tt></span> ��� ������ �� ��� ������� !';
$strPos1 = '�����';
$strPrevious = '����';
$strPrimary = '�����';
$strPrimaryKey = '����� �����';
$strPrimaryKeyHasBeenDropped = '��� �� ��� ������� �������';
$strPrimaryKeyName = '��� ������� ������� ��� �� ���� �����... PRIMARY!';
$strPrimaryKeyWarning = '("�������" <b>���</b> ��� �� ���� ����� <b>������ ���</b> ������� �������!)';
$strPrintView = '��� ���� �������';
$strPrivileges = '����������';
$strProperties = '�����';

$strQBE = '������� ������ ����';
$strQBEDel = 'Del';
$strQBEIns = 'Ins';
$strQueryOnDb = '�� ����� �������� SQL-������� <b>%s</b>:';

$strRecords = '���������';
$strReferentialIntegrity = '����� referential integrity:';
$strReloadFailed = ' ����� ����� �����MySQL.';
$strReloadMySQL = '����� ����� MySQL';
$strRememberReload = '����� ������ ����� ������.';
$strRenameTable = '����� ��� ���� ���';
$strRenameTableOK = '�� ����� ����� ��� %s  ����%s';
$strRepairTable = '����� ������';
$strReplace = '�������';
$strReplaceTable = '������� ������ ������ ������';
$strReset = '�����';
$strReType = '��� �����';
$strRevoke = '�����';
$strRevokeGrant = '����� Grant';
$strRevokeGrantMessage = '��� ����� ������ Grant �� %s';
$strRevokeMessage = '��� ����� ���������� �� %s';
$strRevokePriv = '����� ��������';
$strRowLength = '��� ����';
$strRows = '����';
$strRowsFrom = '���� ���� ��';
$strRowSize = ' ���� ���� ';
$strRowsModeHorizontal = '����';
$strRowsModeOptions = ' %s � ����� ������ ��� %s ���';
$strRowsModeVertical = '�����';
$strRowsStatistic = '��������';
$strRunning = ' ��� ������ %s';
$strRunQuery = '����� ���������';
$strRunSQLQuery = '����� �������/��������� SQL ��� ����� ������ %s';

$strSave = '�����';
$strSelect = '������';
$strSelectADb = '���� ����� ������ �� �������';
$strSelectAll = '����� ����';
$strSelectFields = '������ ���� (��� ����� ����):';
$strSelectNumRows = '�� ���������';
$strSend = '��� ����';
$strServerChoice = '������ ������';
$strServerVersion = '������ ������';
$strSetEnumVal = '��� ��� ��� ����� �� "enum" �� "set", ������ ����� ����� �������� ��� �������: \'a\',\'b\',\'c\'...<br />��� ��� ����� ��� ��� ����� ������ ������� ������ ("\") �� ����� �������� ������� ("\'") ���� ��� ��� �����, ������ ����� ����� ������ (����� \'\\\\xyz\' �� \'a\\\'b\').';
$strShow = '���';
$strShowAll = '���� ����';
$strShowCols = '���� �������';
$strShowingRecords = '������ ������� ';
$strShowPHPInfo = '��� ��������� �������� �  PHP';
$strShowTables = '���� ������';
$strShowThisQuery = ' ��� ��� ��������� ��� ��� ���� ';
$strSingly = '(����)';
$strSize = '�����';
$strSort = '�����';
$strSpaceUsage = '������� ��������';
$strSQLQuery = '�������-SQL';
$strStatement = '�����';
$strStrucCSV = '������ CSV';
$strStrucData = '������ ���������';
$strStrucDrop = ' ����� \'��� ���� ��� ��� ������\' �� �������';
$strStrucExcelCSV = '������ CSV �������  Ms Excel';
$strStrucOnly = '������ ���';
$strSubmit = '�����';
$strSuccess = '����� �� �� ������ ����� SQL-�������';
$strSum = '�������';

$strTable = '������ ';
$strTableComments = '������� ��� ������';
$strTableEmpty = '��� ������ ����!';
$strTableHasBeenDropped = '���� %s �����';
$strTableHasBeenEmptied = '���� %s ������ ���������';
$strTableHasBeenFlushed = '��� �� ����� ����� ������ %s  �����';
$strTableMaintenance = '����� ������';
$strTables = '%s  ���� (�����)';
$strTableStructure = '���� ������';
$strTableType = '��� ������';
$strTextAreaLength = ' ���� ����,<br /> ��� ������� �� ��� ����� ��� ���� ������� ';
$strTheContent = '��� �� ����� ������� ����.';
$strTheContents = '��� �� ������� ������� ������ ������ ������ �������� ������ �� ������� ������� ���� �������� �����.';
$strTheTerminator = '���� ������.';
$strTotal = '�������';
$strType = '�����';

$strUncheckAll = '����� ����� ����';
$strUnique = '����';
$strUnselectAll = '����� ����� ����';
$strUpdatePrivMessage = '��� ���� ����� ���������� �� %s.';
$strUpdateProfile = '����� ����� �������:';
$strUpdateProfileMessage = '��� �� ����� ����� �������.';
$strUpdateQuery = '����� �������';
$strUsage = '�������';
$strUseBackquotes = '����� ����� ������� � ������ � "`" ';
$strUser = '��������';
$strUserEmpty = '��� �������� ����!';
$strUserName = '��� ��������';
$strUsers = '����������';
$strUseTables = '������ ������';

$strValue = '������';
$strViewDump = '��� ���� ������ ';
$strViewDumpDB = '��� ���� ����� ��������';

$strWelcome = '����� �� �� %s';
$strWithChecked = ': ��� ������';
$strWrongUser = '��� ��� ��������/���� ����. ������ �����.';

$strYes = '���';

$strZip = '"zipped" "�����"';

$strAllTableSameWidth = '���� �� ������� ���� ����ֿ';

$strBeginCut = '��� �����';
$strBeginRaw = '��� ������ ������';

$strCantLoadRecodeIconv = '�� ���� ����� iconv �� ����� ����� �������� ������� ������ ����� �����ݡ ������ ����� PHP ����� �������� ��� ���������� �� ��� ��� ������� �� phpMyAdmin.';
$strCantUseRecodeIconv = '�� ���� ������� iconv ��� libiconv ��� ����� recode_string �� ��� ���� �������� ��� �����. ����� �� ������� PHP.';
$strChangeDisplay = '���� ����� �������';
$strCharsetOfFile = '����� ���� �����:';
$strChoosePage = '����� ���� ���� ��������';
$strColComFeat = '����� ������� ������';
$strComments = '�������';
$strConfigFileError = '�� ����� phpMyAdmin �� ���� ��� ��������!<br />�� ���� ��� ���� �� PHP ��� ��� �� ������� ��� �� ��� �� ������ �� ��� �����.<br />����� ���� ����� ���� ����� �������� ������ ����� ����� ����� ����� �������. �� ���� ������� �� ���� ����� ������� �� ����� ������� �������� ����� �� ���� ��.<br />�� ���� ��� ���� ����ɡ ���� ��� ��� �� ����.';
$strConfigureTableCoord = '���� ����� ������ ������ %s';
$strCreatePage = '���� ���� �����';
$strCreatePdfFeat = '����� ����� PDF';

$strDisabled = '�����';
$strDisplayFeat = '����� �������';
$strDisplayPDF = '����� ���� ��� PDF';
$strDumpXRows = '���� %s ��� ���� �� ����� %s.';

$strEditPDFPages = '���� ����� PDF';
$strEnabled = '�����';
$strEndCut = '������ �����';
$strEndRaw = '������ �������� �������';
$strExplain = '���� SQL';
$strExport = '�����';
$strExportToXML = '����� ������ XML';

$strGenBy = '���� ������';
$strGeneralRelationFeat = '������� ������� ������';

$strHaveToShow = '���� ������ ���� ���� ��� ����� �����';

$strLinkNotFound = '�� ���� ����� ������';
$strLinksTo = '����� ��';

$strMissingBracket = '���� ��� ����';
$strMySQLCharset = '����� ���� MySQL';

$strNoDescription = '���� ���';
$strNoExplain = '����� ��� SQL';
$strNoPhp = '���� ����� PHP';
$strNotOK = '��� ������';
$strNotSet = '������ <b>%s</b> ��� ����� �� ���� �� %s';
$strNoValidateSQL = '����� ������� �� SQL';
$strNumSearchResultsInTable = '%s ������ �� ������ <i>%s</i>';
$strNumSearchResultsTotal = '<b>�������:</b> <i>%s</i>������';

$strOK = '�����';
$strOperations = '�������';
$strOptions = '������';

$strPageNumber = '���� ���:';
$strPdfDbSchema = '���� ����� �������� "%s" - ������ %s';
$strPdfInvalidPageNum = '��� ���� PDF ��� �����!';
$strPdfInvalidTblName = '������ "%s" ��� �����!';
$strPdfNoTables = '�� ���� �����';
$strPhp = '���� ����� PHP';

$strRelationNotWorking = '��� ����� ������� �������� ����� �������� ���������. ������ ����� ���� %s���%s.';
$strRelationView = '��� �������';

$strScaleFactorSmall = '���� ����� ������� ����� ��� ������� ������ �� ���� �����.';
$strSearch = '����';
$strSearchFormTitle = '���� �� ����� ��������';
$strSearchInTables = '���� ������)�������(:';
$strSearchNeedle = '������� �� ����� ������� ����� ���� (wildcard: "%"):';
$strSearchOption1 = '��� ����� ��� �������';
$strSearchOption2 = '�� �������';
$strSearchOption3 = '������ ������';
$strSearchOption4 = '����� ������';
$strSearchResultsFor = '���� �� ������� �� "<i>%s</i>" %s:';
$strSearchType = '����:';
$strSelectTables = '���� �������';
$strShowColor = '���� �����';
$strShowGrid = '���� ����� ������';
$strShowTableDimension = '����� ����� �������';
$strSplitWordsWithSpace = '������� ������ ���� ����� (" ").';
$strSQL = 'SQL';
$strSQLParserBugMessage = '���� ������ ��� ���� ��� ��� �� ����� SQL. ����� ����� �������� ����ɡ ������ �� �� ������ ������� ����� ��������. ��� ����� ������� ������ �� ���� ��� ����� ����� ��� ����� ��� ������ ��� ����� ���� ����� �������. ����� ����� ����� �������� ������ ��� ����� MySQL. �� ������ ����� ��� ���� MySQL ����� �� ���� ���� ����ɡ ��� ����� �������. �� ��� ���� ����� �� �� ���� ������� �� ��� ��� ������� ��� ������ѡ ����� ���� ��� �������� �������� ���� ���� ������ɡ ��� ������ ����� ��� �� ��� �������� �� ����� ����� �����:';
$strSQLParserUserError = '���� �� ���� ��� �� ������� SQL. ��� ������ ����� ����� �� ���� MySQL ����� �� ����� ������ɡ �� ��� ���� ����ɡ.';
$strSQLResult = '���� ������� SQL';
$strSQPBugInvalidIdentifer = '����� ��� ����';
$strSQPBugUnclosedQuote = '����� ����� ��� �����';
$strSQPBugUnknownPunctuation = '�� ����� ��� �����';
$strStructPropose = '����� ���� ������';
$strStructure = '����';

$strValidateSQL = '������ �� ������� SQL';

$strInsecureMySQL = 'Your configuration file contains settings (root with no password) that correspond to the default MySQL privileged account. Your MySQL server is running with this default, is open to intrusion, and you really should fix this security hole.';
$strWebServerUploadDirectory = '���� ����� ������� ��� ���� ������';
$strWebServerUploadDirectoryError = 'The directory you set for upload work cannot be reached';
$strValidatorError = 'The SQL validator could not be initialized. Please check if you have installed the necessary php extensions as described in the %sdocumentation%s.';
$strServer = '���� %s';
$strPutColNames = '�� ����� ������ �� ����� �����';
$strImportDocSQL = '������� ����� docSQL';
$strDataDict = '����� ��������';
$strPrint = '����';
$strPHP40203 = '��� ������ ������� 4.2.3 �� PHP ����� ����� ��� ���� ���� �� ������� �� ������ ������ ������ (mbstring). ���� �� ����� ��� PHP ��� 19404. �� ���� �������� ��� ������ �� PHP �� phpMyAdmin.';
$strCompression = '�����';
$strNumTables = '�����';
$strTotalUC = '����� ����';
$strRelationalSchema = '���� ����������';
$strTableOfContents = '���� ���������';
$strCannotLogin = '�� ���� ������ ��� ���� MySQL';
$strShowDatadictAs = '����� ����� ��������';
$strLandscape = '��� ������';
$strPortrait = '��� ������';

$timespanfmt = '%s ��� %s ���ɡ %s ����� �%s �����';

$strAbortedClients = '����';
$strConnections = '�������';
$strFailedAttempts = '������� �����';
$strGlobalValue = '���� �����';
$strMoreStatusVars = '�������� ���� ������';
$strPerHour = '��� ����';
$strQueryStatistics = '<b>�������� ���������</b>: %s ������� ���� ��� ������ ��� ������.';
$strQueryType = '��� ���������';
$strReceived = '�������';
$strSent = '������';
$strServerStatus = '������ �������';
$strServerStatusUptime = '��� ��� ��� ���� MySQL ��� %s. ��� ����� �� %s.';
$strServerTabVariables = '��������';
$strServerTabProcesslist = '��������';
$strServerTrafficNotes = '<b>���� ������</b>: ���� ��� ������� �������� ���� ������ ������ ���� ������ ��� ������.';
$strServerVars = '�������� �������� ������';
$strSessionValue = '���� ������';
$strTraffic = '������ ���';
$strVar = '������';

$strCommand = '����';
$strCouldNotKill = '�� ����� phpMyAdmin ����� �������� %s. ���� ���� ����� ������.';
$strId = '���';
$strProcesslist = '��� ���������';
$strStatus = '����';
$strTime = '���';
$strThreadSuccessfullyKilled = '�� ����� �������� %s �����.';

$strBzError = '�� ����� phpMyAdmin ��� ��� �������� ���� ��� �� ������ Bz2 �� ����� PHP. ������ �� ����� ���� ����� <code>$cfg[\'BZipDump\']</code> �� ��� ������� phpMyAdmin ��� <code>FALSE</code>. �� ��� ���� ������� ����� ��� Bz2� ���� �������� ��� ����� ���� �� PHP. ����� �� �������� ���� �� ����� ��� PHP %s.';
$strLaTeX = '��������';

$strAdministration = '�����';
$strFlushPrivilegesNote = '������: ���� phpMyAdmin ������� ���������� �� ����� ��������� �� ���� MySQL �������. ������� ��� ������� �� ����� �� ��������� ���� �������� ������ ��� �� ��� ������� ����� �������. �� ��� �����ɡ ���� %s ������ ����� ��������� %s ��� �� ����.';
$strGlobalPrivileges = '�������� �����';
$strGrantOption = '������';
$strPrivDescAllPrivileges = '������ �� ���������� ��� GRANT.';
$strPrivDescAlter = '���� ������ ���� ������� �������� ������.';
$strPrivDescCreateDb = '���� ������ ����� ������ ������ �����.';
$strPrivDescCreateTbl = '���� ������ ����� �����.';
$strPrivDescCreateTmpTable = '���� ������ ����� ������.';
$strPrivDescDelete = '���� ���� ��������.';
$strPrivDescDropDb = '���� ���� ����� ��������.';
$strPrivDescDropTbl = '���� ���� �������.';
$strPrivDescExecute = '���� ������ ��������� �������� )stored procedures(� ��� �� �� ����� �� ��� ������ �� ���� MySQL.';
$strPrivDescFile = '���� �������� ������ �������� �� ���� ��������.';
$strPrivDescGrant = '���� ������ ���������� ���������� ��� ����� ����� ����� ���������.';
$strPrivDescIndex = '���� ������ ���� �������.';
$strPrivDescInsert = '���� ������ �������� ��������.';
$strPrivDescLockTables = '���� ���� ������� �������� ��������.';
$strPrivDescMaxConnections = '���� �� ��� ��������� ������� ���� ���� �������� ����� ��� ����.';
$strPrivDescMaxQuestions = '���� ��� ����������� ���� ������ �������� ������� ��� ������ ��� ����.';
$strPrivDescMaxUpdates = '���� ��� ������� ���� ������ �������� ��� ���ɡ ����� ���� �� ���� �� ����� ������.';
$strPrivDescProcess3 = '���� ������ ������� ���������� �������.';
$strPrivDescProcess4 = '���� ���� ��������� ������� �� ��� ��������.';
$strPrivDescReferences = '��� �� �� ����� �� ���� MySQL ��������.';
$strPrivDescReplClient = 'Gives the right to the user to ask where the slaves / masters are.';
$strPrivDescReplSlave = '����� ������ ��������.';
$strPrivDescReload = 'Allows reloading server settings and flushing the server\'s caches.';
$strPrivDescSelect = '���� ������ ��������.';
$strPrivDescShowDb = '���� ������� ������ ����� ���� ����� ��������.';
$strPrivDescShutdown = '���� ������ ��� ������.';
$strPrivDescSuper = '���� �������� ��� �� ��� ��� ��� ��������� ������.� ����� ������ �������� ���� ��������� ������� other users.';
$strPrivDescUpdate = '���� ������ ��������.';
$strPrivDescUsage = '�� �������.';
$strPrivilegesReloaded = '�� ����� ����� ��������� �����.';
$strResourceLimits = '���� �������';
$strUserOverview = '������� ��������';
$strZeroRemovesTheLimit = '������: ����� ��� �������� ����� 0 )���( ���� �����.';

$strPasswordChanged = '�� ����� ���� ������ �� %s �����.';

$strDeleteAndFlush = '���� ���������� ��� ������ ����� ��������� ��� ���.';
$strDeleteAndFlushDescr = '��� �� ���� ����ɡ ��� ����� ����� ��������� �� ������ ��� �����.';
$strDeleting = '���� ���� %s';
$strJustDelete = '��� �� ���� ���������� �� ���� ���������.';
$strJustDeleteDescr = '��� ���� ���������� &quot;���������&quot; ������ ��� ������ ������ ������� ��� ��� ����� ����� ���������.';
$strReloadingThePrivileges = '��� ����� ����� ���������.';
$strRemoveSelectedUsers = '���� ���������� ��������';
$strRevokeAndDelete = '������ �� ��������� ������� �� ���������� �� ������ ��� ���.';
$strRevokeAndDeleteDescr = '��� ���� �������� USAGE ��� ���������� ��� ��� ����� ����� ���������.';
$strUsersDeleted = '�� ��� ���������� �������� �����.';

$strAddPrivilegesOnDb = '����� ��������� ��� ����� �������� �������';
$strAddPrivilegesOnTbl = '����� ��������� ��� ������ ������';
$strColumnPrivileges = '������� ���� ������';
$strDbPrivileges = '������� ���� ������ ��������';
$strLocalhost = '����';
$strLoginInformation = '������ ������';
$strTblPrivileges = '������� ���� �������';
$strThisHost = '��� ������';
$strUserNotFound = '�������� ������ ��� ����� �� ���� ���������.';
$strUserAlreadyExists = '��� �������� %s ����� ������!';
$strUseTextField = '������ ��� ���';

$strNoUsersSelected = '�� ��� ����� ������.';
$strDropUsersDb = '���� ����� �������� ���� ��� ��� ����� ����������.';
$strAddedColumnComment = '�� ����� ������� ������';
$strWritingCommentNotPossible = '����� ������� ��� ����';
$strAddedColumnRelation = '�� ����� ������� ������';
$strWritingRelationNotPossible = '����� ������� ��� �����';
$strImportFinished = '����� ���������';
$strFileCouldNotBeRead = '�� ���� ����� �����';
$strIgnoringFile = '����� ����� %s';
$strThisNotDirectory = '�� ��� ��� ������';
$strAbsolutePathToDocSqlDir = '������ ����� ������ ������ ��� ���� ������ ��� ���� docSQL';
$strImportFiles = '������ �������';
$strDBGModule = '������';
$strDBGLine = '���';
$strDBGHits = '���������';
$strDBGTimePerHitMs = '���/������� ��';
$strDBGTotalTimeMs = '����� ������ ��';
$strDBGMinTimeMs = '��� ��ʡ ��';
$strDBGMaxTimeMs = '���� ��ʡ ��';
$strDBGContextID = '��� ������';
$strDBGContext = '������';
$strCantLoad = '�� ���� ����� �������� %s�<br />���� ���� �� ������� PHP.';
$strDefaultValueHelp = 'For default values, please enter just a single value, without backslash escaping or quotes, using this format: a';  //to translate
$strCheckPrivs = 'Check Privileges';  //to translate
$strCheckPrivsLong = 'Check privileges for database &quot;%s&quot;.';  //to translate
$strDatabasesStatsHeavyTraffic = 'Note: Enabling the Database statistics here might cause heavy traffic between the webserver and the MySQL one.';  //to translate
$strDatabasesStatsDisable = 'Disable Statistics';  //to translate
$strDatabasesStatsEnable = 'Enable Statistics';  //to translate
$strJumpToDB = 'Jump to database &quot;%s&quot;.';  //to translate
$strDropSelectedDatabases = 'Drop Selected Databases';  //to translate
$strNoDatabasesSelected = 'No databases selected.';  //to translate
$strDatabasesDropped = '%s databases have been dropped successfully.';  //to translate
$strGlobal = 'global';  //to translate
$strDbSpecific = 'database-specific';  //to translate
$strUsersHavingAccessToDb = 'Users having access to &quot;%s&quot;';  //to translate
$strChangeCopyUser = 'Change Login Information / Copy User';  //to translate
$strChangeCopyMode = 'Create a new user with the same privileges and ...';  //to translate
$strChangeCopyModeCopy = '... keep the old one.';  //to translate
$strChangeCopyModeJustDelete = ' ... delete the old one from the user tables.';  //to translate
$strChangeCopyModeRevoke = ' ... revoke all active privileges from the old one and delete it afterwards.';  //to translate
$strChangeCopyModeDeleteAndReload = ' ... delete the old one from the user tables and reload the privileges afterwards.';  //to translate
$strWildcard = 'wildcard';  //to translate
$strRowsModeFlippedHorizontal = 'horizontal (rotated headers)';//to translate
$strQueryTime = 'Query took %01.4f sec';//to translate
$strDumpComments = 'Include column comments as inline SQL-comments';//to translate
$strDBComment = 'Database comment: ';//to translate
$strQueryFrame = 'Query window';//to translate
$strQueryFrameDebug = 'Debugging information';//to translate
$strQueryFrameDebugBox = 'Active variables for the query form:\nDB: %s\nTable: %s\nServer: %s\n\nCurrent variables for the query form:\nDB: %s\nTable: %s\nServer: %s\n\nOpener location: %s\nFrameset location: %s.';//to translate
$strQuerySQLHistory = 'SQL-history';//to translate
$strMIME_MIMEtype = 'MIME-type';//to translate
$strMIME_transformation = 'Browser transformation';//to translate
$strMIME_transformation_options = 'Transformation options';//to translate
$strMIME_transformation_options_note = 'Please enter the values for transformation options using this format: \'a\',\'b\',\'c\'...<br />If you ever need to put a backslash ("\") or a single quote ("\'") amongst those values, backslashes it (for example \'\\\\xyz\' or \'a\\\'b\').';//to translate
$strMIME_transformation_note = 'For a list of available transformation options and their MIME-type transformations, click on %stransformation descriptions%s';//to translate
$strMIME_available_mime = 'Available MIME-types';//to translate
$strMIME_available_transform = 'Available transformations';//to translate
$strMIME_without = 'MIME-types printed in italics do not have a seperate transformation function';//to translate
$strMIME_description = 'Description';//to translate
$strMIME_nodescription = 'No Description is available for this transformation.<br />Please ask the author, what %s does.';//to translate
$strMIME_file = 'Filename';//to translate
$strTransformation_text_plain__formatted = 'Preserves original formatting of the field. No Escaping is done.';//to translate
$strTransformation_text_plain__unformatted = 'Displays HTML code as HTML entities. No HTML formatting is shown.';//to translate
$strTransformation_image_jpeg__link = 'Displays a link to this image (direct blob download, i.e.).';//to translate
$strInnodbStat = 'InnoDB Status';  //to translate
$strUpdComTab = 'Please see Documentation on how to update your Column_comments Table';  //to translate
$strTransformation_image_jpeg__inline = 'Displays a clickable thumbnail; options: width,height in pixels (keeps the original ratio)';  //to translate
$strTransformation_image_png__inline = 'See image/jpeg: inline';  //to translate
$strSQLOptions = 'SQL options';//to translate
$strXML = 'XML';//to translate
$strCSVOptions = 'CSV options';//to translate
$strNoOptions = 'This format has no options';//to translate
$strStatCreateTime = 'Creation';//to translate
$strStatUpdateTime = 'Last update';//to translate
$strStatCheckTime = 'Last check';//to translate
$strPerMinute = 'per minute';//to translate
$strPerSecond = 'per second';//to translate
$strAutomaticLayout = 'Automatic layout';  //to translate
$strDelOld = 'The current Page has References to Tables that no longer exist. Would you like to delete those References?';  //to translate
$strFileNameTemplate = 'File name template';//to translate
$strFileNameTemplateRemember = 'remember template';//to translate
$strFileNameTemplateHelp = 'Use __DB__ for database name, __TABLE__ for table name and %sany strftime%s options for time specification, extension will be automagically added. Any other text will be preserved.';//to translate
$strTransformation_text_plain__dateformat = 'Takes a TIME, TIMESTAMP or DATETIME field and formats it using your local dateformat. First option is the offset (in hours) which will be added to the timestamp (Default: 0). Second option is a different dateformat according to the parameters available for PHPs strftime().';//to translate
$strTransformation_text_plain__substr = 'Only shows part of a string. First option is an offset to define where the output of your text starts (Default 0). Second option is an offset how much text is returned. If empty, returns all the remaining text. The third option defines which chars will be appended to the output when a substring is returned (Default: ...) .';//to translate
$strTransformation_text_plain__external = 'LINUX ONLY: Launches an external application and feeds the fielddata via standard input. Returns standard output of the application. Default is Tidy, to pretty print HTML code. For security reasons, you have to manually edit the file libraries/transformations/text_plain__external.inc.php and insert the tools you allow to be run. The first option is then the number of the program you want to use and the second option are the parameters for the program. The third parameter, if set to 1 will convert the output using htmlspecialchars() (Default is 1). A fourth parameter, if set to 1 will put a NOWRAP to the content cell so that the whole output will be shown without reformatting (Default 1)';//to translate
$strAutodetect = 'Autodetect';  //to translate
$strTransformation_text_plain__imagelink = 'Displays an image and a link, the field contains the filename; first option is a prefix like "http://domain.com/", second option is the width in pixels, third is the height.';  //to translate
$strTransformation_text_plain__link = 'Displays a link, the field contains the filename; first option is a prefix like "http://domain.com/", second option is a title for the link.';  //to translate
$strUseHostTable = 'Use Host Table';  //to translate
$strShowFullQueries = 'Show Full Queries';  //to translate
$strTruncateQueries = 'Truncate Shown Queries';  //to translate
$strSwitchToTable = 'Switch to copied table';  //to translate
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
