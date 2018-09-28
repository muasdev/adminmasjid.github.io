<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "tanamaninfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$tanaman_add = NULL; // Initialize page object first

class ctanaman_add extends ctanaman {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{16949F2C-0374-40AA-AABE-77D7F58B6C00}';

	// Table name
	var $TableName = 'tanaman';

	// Page object name
	var $PageObjName = 'tanaman_add';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (tanaman)
		if (!isset($GLOBALS["tanaman"]) || get_class($GLOBALS["tanaman"]) == "ctanaman") {
			$GLOBALS["tanaman"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tanaman"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tanaman', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->foto_tanaman->SetVisibility();
		$this->nama_ilmiah->SetVisibility();
		$this->nama_lokal->SetVisibility();
		$this->famili_tanaman->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $tanaman;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tanaman);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "tanamanview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("tanamanlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tanamanlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "tanamanview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->foto_tanaman->Upload->Index = $objForm->Index;
		$this->foto_tanaman->Upload->UploadFile();
		$this->foto_tanaman->CurrentValue = $this->foto_tanaman->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->foto_tanaman->Upload->DbValue = NULL;
		$this->foto_tanaman->OldValue = $this->foto_tanaman->Upload->DbValue;
		$this->foto_tanaman->CurrentValue = NULL; // Clear file related field
		$this->nama_ilmiah->CurrentValue = NULL;
		$this->nama_ilmiah->OldValue = $this->nama_ilmiah->CurrentValue;
		$this->nama_lokal->CurrentValue = NULL;
		$this->nama_lokal->OldValue = $this->nama_lokal->CurrentValue;
		$this->famili_tanaman->CurrentValue = NULL;
		$this->famili_tanaman->OldValue = $this->famili_tanaman->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->nama_ilmiah->FldIsDetailKey) {
			$this->nama_ilmiah->setFormValue($objForm->GetValue("x_nama_ilmiah"));
		}
		if (!$this->nama_lokal->FldIsDetailKey) {
			$this->nama_lokal->setFormValue($objForm->GetValue("x_nama_lokal"));
		}
		if (!$this->famili_tanaman->FldIsDetailKey) {
			$this->famili_tanaman->setFormValue($objForm->GetValue("x_famili_tanaman"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->nama_ilmiah->CurrentValue = $this->nama_ilmiah->FormValue;
		$this->nama_lokal->CurrentValue = $this->nama_lokal->FormValue;
		$this->famili_tanaman->CurrentValue = $this->famili_tanaman->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->id->setDbValue($row['id']);
		$this->foto_tanaman->Upload->DbValue = $row['foto_tanaman'];
		$this->foto_tanaman->setDbValue($this->foto_tanaman->Upload->DbValue);
		$this->nama_ilmiah->setDbValue($row['nama_ilmiah']);
		$this->nama_lokal->setDbValue($row['nama_lokal']);
		$this->famili_tanaman->setDbValue($row['famili_tanaman']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['foto_tanaman'] = $this->foto_tanaman->Upload->DbValue;
		$row['nama_ilmiah'] = $this->nama_ilmiah->CurrentValue;
		$row['nama_lokal'] = $this->nama_lokal->CurrentValue;
		$row['famili_tanaman'] = $this->famili_tanaman->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->foto_tanaman->Upload->DbValue = $row['foto_tanaman'];
		$this->nama_ilmiah->DbValue = $row['nama_ilmiah'];
		$this->nama_lokal->DbValue = $row['nama_lokal'];
		$this->famili_tanaman->DbValue = $row['famili_tanaman'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// foto_tanaman
		// nama_ilmiah
		// nama_lokal
		// famili_tanaman

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// foto_tanaman
		if (!ew_Empty($this->foto_tanaman->Upload->DbValue)) {
			$this->foto_tanaman->ViewValue = $this->foto_tanaman->Upload->DbValue;
		} else {
			$this->foto_tanaman->ViewValue = "";
		}
		$this->foto_tanaman->ViewCustomAttributes = "";

		// nama_ilmiah
		$this->nama_ilmiah->ViewValue = $this->nama_ilmiah->CurrentValue;
		$this->nama_ilmiah->ViewCustomAttributes = "";

		// nama_lokal
		$this->nama_lokal->ViewValue = $this->nama_lokal->CurrentValue;
		$this->nama_lokal->ViewCustomAttributes = "";

		// famili_tanaman
		$this->famili_tanaman->ViewValue = $this->famili_tanaman->CurrentValue;
		$this->famili_tanaman->ViewCustomAttributes = "";

			// foto_tanaman
			$this->foto_tanaman->LinkCustomAttributes = "";
			$this->foto_tanaman->HrefValue = "";
			$this->foto_tanaman->HrefValue2 = $this->foto_tanaman->UploadPath . $this->foto_tanaman->Upload->DbValue;
			$this->foto_tanaman->TooltipValue = "";

			// nama_ilmiah
			$this->nama_ilmiah->LinkCustomAttributes = "";
			$this->nama_ilmiah->HrefValue = "";
			$this->nama_ilmiah->TooltipValue = "";

			// nama_lokal
			$this->nama_lokal->LinkCustomAttributes = "";
			$this->nama_lokal->HrefValue = "";
			$this->nama_lokal->TooltipValue = "";

			// famili_tanaman
			$this->famili_tanaman->LinkCustomAttributes = "";
			$this->famili_tanaman->HrefValue = "";
			$this->famili_tanaman->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// foto_tanaman
			$this->foto_tanaman->EditAttrs["class"] = "form-control";
			$this->foto_tanaman->EditCustomAttributes = "";
			if (!ew_Empty($this->foto_tanaman->Upload->DbValue)) {
				$this->foto_tanaman->EditValue = $this->foto_tanaman->Upload->DbValue;
			} else {
				$this->foto_tanaman->EditValue = "";
			}
			if (!ew_Empty($this->foto_tanaman->CurrentValue))
					$this->foto_tanaman->Upload->FileName = $this->foto_tanaman->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->foto_tanaman);

			// nama_ilmiah
			$this->nama_ilmiah->EditAttrs["class"] = "form-control";
			$this->nama_ilmiah->EditCustomAttributes = "";
			$this->nama_ilmiah->EditValue = ew_HtmlEncode($this->nama_ilmiah->CurrentValue);
			$this->nama_ilmiah->PlaceHolder = ew_RemoveHtml($this->nama_ilmiah->FldCaption());

			// nama_lokal
			$this->nama_lokal->EditAttrs["class"] = "form-control";
			$this->nama_lokal->EditCustomAttributes = "";
			$this->nama_lokal->EditValue = ew_HtmlEncode($this->nama_lokal->CurrentValue);
			$this->nama_lokal->PlaceHolder = ew_RemoveHtml($this->nama_lokal->FldCaption());

			// famili_tanaman
			$this->famili_tanaman->EditAttrs["class"] = "form-control";
			$this->famili_tanaman->EditCustomAttributes = "";
			$this->famili_tanaman->EditValue = ew_HtmlEncode($this->famili_tanaman->CurrentValue);
			$this->famili_tanaman->PlaceHolder = ew_RemoveHtml($this->famili_tanaman->FldCaption());

			// Add refer script
			// foto_tanaman

			$this->foto_tanaman->LinkCustomAttributes = "";
			$this->foto_tanaman->HrefValue = "";
			$this->foto_tanaman->HrefValue2 = $this->foto_tanaman->UploadPath . $this->foto_tanaman->Upload->DbValue;

			// nama_ilmiah
			$this->nama_ilmiah->LinkCustomAttributes = "";
			$this->nama_ilmiah->HrefValue = "";

			// nama_lokal
			$this->nama_lokal->LinkCustomAttributes = "";
			$this->nama_lokal->HrefValue = "";

			// famili_tanaman
			$this->famili_tanaman->LinkCustomAttributes = "";
			$this->famili_tanaman->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if ($this->foto_tanaman->Upload->FileName == "" && !$this->foto_tanaman->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->foto_tanaman->FldCaption(), $this->foto_tanaman->ReqErrMsg));
		}
		if (!$this->nama_ilmiah->FldIsDetailKey && !is_null($this->nama_ilmiah->FormValue) && $this->nama_ilmiah->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nama_ilmiah->FldCaption(), $this->nama_ilmiah->ReqErrMsg));
		}
		if (!$this->nama_lokal->FldIsDetailKey && !is_null($this->nama_lokal->FormValue) && $this->nama_lokal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nama_lokal->FldCaption(), $this->nama_lokal->ReqErrMsg));
		}
		if (!$this->famili_tanaman->FldIsDetailKey && !is_null($this->famili_tanaman->FormValue) && $this->famili_tanaman->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->famili_tanaman->FldCaption(), $this->famili_tanaman->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// foto_tanaman
		if ($this->foto_tanaman->Visible && !$this->foto_tanaman->Upload->KeepFile) {
			$this->foto_tanaman->Upload->DbValue = ""; // No need to delete old file
			if ($this->foto_tanaman->Upload->FileName == "") {
				$rsnew['foto_tanaman'] = NULL;
			} else {
				$rsnew['foto_tanaman'] = $this->foto_tanaman->Upload->FileName;
			}
		}

		// nama_ilmiah
		$this->nama_ilmiah->SetDbValueDef($rsnew, $this->nama_ilmiah->CurrentValue, "", FALSE);

		// nama_lokal
		$this->nama_lokal->SetDbValueDef($rsnew, $this->nama_lokal->CurrentValue, "", FALSE);

		// famili_tanaman
		$this->famili_tanaman->SetDbValueDef($rsnew, $this->famili_tanaman->CurrentValue, "", FALSE);
		if ($this->foto_tanaman->Visible && !$this->foto_tanaman->Upload->KeepFile) {
			$OldFiles = ew_Empty($this->foto_tanaman->Upload->DbValue) ? array() : array($this->foto_tanaman->Upload->DbValue);
			if (!ew_Empty($this->foto_tanaman->Upload->FileName)) {
				$NewFiles = array($this->foto_tanaman->Upload->FileName);
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->foto_tanaman->Upload->Index < 0) ? $this->foto_tanaman->FldVar : substr($this->foto_tanaman->FldVar, 0, 1) . $this->foto_tanaman->Upload->Index . substr($this->foto_tanaman->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->foto_tanaman->TblVar) . $file)) {
							$OldFileFound = FALSE;
							$OldFileCount = count($OldFiles);
							for ($j = 0; $j < $OldFileCount; $j++) {
								$file1 = $OldFiles[$j];
								if ($file1 == $file) { // Old file found, no need to delete anymore
									unset($OldFiles[$j]);
									$OldFileFound = TRUE;
									break;
								}
							}
							if ($OldFileFound) // No need to check if file exists further
								continue;
							$file1 = ew_UploadFileNameEx($this->foto_tanaman->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->foto_tanaman->TblVar) . $file1) || file_exists($this->foto_tanaman->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->foto_tanaman->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->foto_tanaman->TblVar) . $file, ew_UploadTempPath($fldvar, $this->foto_tanaman->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->foto_tanaman->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->foto_tanaman->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->foto_tanaman->SetDbValueDef($rsnew, $this->foto_tanaman->Upload->FileName, "", FALSE);
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if ($this->foto_tanaman->Visible && !$this->foto_tanaman->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->foto_tanaman->Upload->DbValue) ? array() : array($this->foto_tanaman->Upload->DbValue);
					if (!ew_Empty($this->foto_tanaman->Upload->FileName)) {
						$NewFiles = array($this->foto_tanaman->Upload->FileName);
						$NewFiles2 = array($rsnew['foto_tanaman']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->foto_tanaman->Upload->Index < 0) ? $this->foto_tanaman->FldVar : substr($this->foto_tanaman->FldVar, 0, 1) . $this->foto_tanaman->Upload->Index . substr($this->foto_tanaman->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->foto_tanaman->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->foto_tanaman->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
										$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
										return FALSE;
									}
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$OldFileCount = count($OldFiles);
					for ($i = 0; $i < $OldFileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink($this->foto_tanaman->OldPhysicalUploadPath() . $OldFiles[$i]);
					}
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// foto_tanaman
		ew_CleanUploadTempPath($this->foto_tanaman, $this->foto_tanaman->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tanamanlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tanaman_add)) $tanaman_add = new ctanaman_add();

// Page init
$tanaman_add->Page_Init();

// Page main
$tanaman_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tanaman_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ftanamanadd = new ew_Form("ftanamanadd", "add");

// Validate form
ftanamanadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			felm = this.GetElements("x" + infix + "_foto_tanaman");
			elm = this.GetElements("fn_x" + infix + "_foto_tanaman");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $tanaman->foto_tanaman->FldCaption(), $tanaman->foto_tanaman->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nama_ilmiah");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanaman->nama_ilmiah->FldCaption(), $tanaman->nama_ilmiah->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nama_lokal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanaman->nama_lokal->FldCaption(), $tanaman->nama_lokal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_famili_tanaman");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanaman->famili_tanaman->FldCaption(), $tanaman->famili_tanaman->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ftanamanadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
ftanamanadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $tanaman_add->ShowPageHeader(); ?>
<?php
$tanaman_add->ShowMessage();
?>
<form name="ftanamanadd" id="ftanamanadd" class="<?php echo $tanaman_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tanaman_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tanaman_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tanaman">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($tanaman_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($tanaman->foto_tanaman->Visible) { // foto_tanaman ?>
	<div id="r_foto_tanaman" class="form-group">
		<label id="elh_tanaman_foto_tanaman" class="<?php echo $tanaman_add->LeftColumnClass ?>"><?php echo $tanaman->foto_tanaman->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $tanaman_add->RightColumnClass ?>"><div<?php echo $tanaman->foto_tanaman->CellAttributes() ?>>
<span id="el_tanaman_foto_tanaman">
<div id="fd_x_foto_tanaman">
<span title="<?php echo $tanaman->foto_tanaman->FldTitle() ? $tanaman->foto_tanaman->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($tanaman->foto_tanaman->ReadOnly || $tanaman->foto_tanaman->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="tanaman" data-field="x_foto_tanaman" name="x_foto_tanaman" id="x_foto_tanaman"<?php echo $tanaman->foto_tanaman->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_foto_tanaman" id= "fn_x_foto_tanaman" value="<?php echo $tanaman->foto_tanaman->Upload->FileName ?>">
<input type="hidden" name="fa_x_foto_tanaman" id= "fa_x_foto_tanaman" value="0">
<input type="hidden" name="fs_x_foto_tanaman" id= "fs_x_foto_tanaman" value="1000">
<input type="hidden" name="fx_x_foto_tanaman" id= "fx_x_foto_tanaman" value="<?php echo $tanaman->foto_tanaman->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_foto_tanaman" id= "fm_x_foto_tanaman" value="<?php echo $tanaman->foto_tanaman->UploadMaxFileSize ?>">
</div>
<table id="ft_x_foto_tanaman" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $tanaman->foto_tanaman->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanaman->nama_ilmiah->Visible) { // nama_ilmiah ?>
	<div id="r_nama_ilmiah" class="form-group">
		<label id="elh_tanaman_nama_ilmiah" for="x_nama_ilmiah" class="<?php echo $tanaman_add->LeftColumnClass ?>"><?php echo $tanaman->nama_ilmiah->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $tanaman_add->RightColumnClass ?>"><div<?php echo $tanaman->nama_ilmiah->CellAttributes() ?>>
<span id="el_tanaman_nama_ilmiah">
<input type="text" data-table="tanaman" data-field="x_nama_ilmiah" name="x_nama_ilmiah" id="x_nama_ilmiah" placeholder="<?php echo ew_HtmlEncode($tanaman->nama_ilmiah->getPlaceHolder()) ?>" value="<?php echo $tanaman->nama_ilmiah->EditValue ?>"<?php echo $tanaman->nama_ilmiah->EditAttributes() ?>>
</span>
<?php echo $tanaman->nama_ilmiah->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanaman->nama_lokal->Visible) { // nama_lokal ?>
	<div id="r_nama_lokal" class="form-group">
		<label id="elh_tanaman_nama_lokal" for="x_nama_lokal" class="<?php echo $tanaman_add->LeftColumnClass ?>"><?php echo $tanaman->nama_lokal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $tanaman_add->RightColumnClass ?>"><div<?php echo $tanaman->nama_lokal->CellAttributes() ?>>
<span id="el_tanaman_nama_lokal">
<input type="text" data-table="tanaman" data-field="x_nama_lokal" name="x_nama_lokal" id="x_nama_lokal" placeholder="<?php echo ew_HtmlEncode($tanaman->nama_lokal->getPlaceHolder()) ?>" value="<?php echo $tanaman->nama_lokal->EditValue ?>"<?php echo $tanaman->nama_lokal->EditAttributes() ?>>
</span>
<?php echo $tanaman->nama_lokal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanaman->famili_tanaman->Visible) { // famili_tanaman ?>
	<div id="r_famili_tanaman" class="form-group">
		<label id="elh_tanaman_famili_tanaman" for="x_famili_tanaman" class="<?php echo $tanaman_add->LeftColumnClass ?>"><?php echo $tanaman->famili_tanaman->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $tanaman_add->RightColumnClass ?>"><div<?php echo $tanaman->famili_tanaman->CellAttributes() ?>>
<span id="el_tanaman_famili_tanaman">
<input type="text" data-table="tanaman" data-field="x_famili_tanaman" name="x_famili_tanaman" id="x_famili_tanaman" placeholder="<?php echo ew_HtmlEncode($tanaman->famili_tanaman->getPlaceHolder()) ?>" value="<?php echo $tanaman->famili_tanaman->EditValue ?>"<?php echo $tanaman->famili_tanaman->EditAttributes() ?>>
</span>
<?php echo $tanaman->famili_tanaman->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$tanaman_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $tanaman_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tanaman_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
ftanamanadd.Init();
</script>
<?php
$tanaman_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tanaman_add->Page_Terminate();
?>
