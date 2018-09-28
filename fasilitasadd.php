<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "fasilitasinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$fasilitas_add = NULL; // Initialize page object first

class cfasilitas_add extends cfasilitas {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{16949F2C-0374-40AA-AABE-77D7F58B6C00}';

	// Table name
	var $TableName = 'fasilitas';

	// Page object name
	var $PageObjName = 'fasilitas_add';

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

		// Table object (fasilitas)
		if (!isset($GLOBALS["fasilitas"]) || get_class($GLOBALS["fasilitas"]) == "cfasilitas") {
			$GLOBALS["fasilitas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fasilitas"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fasilitas', TRUE);

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
		$this->foto_fasilitas->SetVisibility();
		$this->nama_fasilitas->SetVisibility();
		$this->deskripsi->SetVisibility();

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
		global $EW_EXPORT, $fasilitas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($fasilitas);
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
					if ($pageName == "fasilitasview.php")
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
					$this->Page_Terminate("fasilitaslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "fasilitaslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "fasilitasview.php")
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
		$this->foto_fasilitas->Upload->Index = $objForm->Index;
		$this->foto_fasilitas->Upload->UploadFile();
		$this->foto_fasilitas->CurrentValue = $this->foto_fasilitas->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->foto_fasilitas->Upload->DbValue = NULL;
		$this->foto_fasilitas->OldValue = $this->foto_fasilitas->Upload->DbValue;
		$this->foto_fasilitas->CurrentValue = NULL; // Clear file related field
		$this->nama_fasilitas->CurrentValue = NULL;
		$this->nama_fasilitas->OldValue = $this->nama_fasilitas->CurrentValue;
		$this->deskripsi->CurrentValue = NULL;
		$this->deskripsi->OldValue = $this->deskripsi->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->nama_fasilitas->FldIsDetailKey) {
			$this->nama_fasilitas->setFormValue($objForm->GetValue("x_nama_fasilitas"));
		}
		if (!$this->deskripsi->FldIsDetailKey) {
			$this->deskripsi->setFormValue($objForm->GetValue("x_deskripsi"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->nama_fasilitas->CurrentValue = $this->nama_fasilitas->FormValue;
		$this->deskripsi->CurrentValue = $this->deskripsi->FormValue;
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
		$this->foto_fasilitas->Upload->DbValue = $row['foto_fasilitas'];
		$this->foto_fasilitas->setDbValue($this->foto_fasilitas->Upload->DbValue);
		$this->nama_fasilitas->setDbValue($row['nama_fasilitas']);
		$this->deskripsi->setDbValue($row['deskripsi']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['foto_fasilitas'] = $this->foto_fasilitas->Upload->DbValue;
		$row['nama_fasilitas'] = $this->nama_fasilitas->CurrentValue;
		$row['deskripsi'] = $this->deskripsi->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->foto_fasilitas->Upload->DbValue = $row['foto_fasilitas'];
		$this->nama_fasilitas->DbValue = $row['nama_fasilitas'];
		$this->deskripsi->DbValue = $row['deskripsi'];
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
		// foto_fasilitas
		// nama_fasilitas
		// deskripsi

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// foto_fasilitas
		if (!ew_Empty($this->foto_fasilitas->Upload->DbValue)) {
			$this->foto_fasilitas->ViewValue = $this->foto_fasilitas->Upload->DbValue;
		} else {
			$this->foto_fasilitas->ViewValue = "";
		}
		$this->foto_fasilitas->ViewCustomAttributes = "";

		// nama_fasilitas
		$this->nama_fasilitas->ViewValue = $this->nama_fasilitas->CurrentValue;
		$this->nama_fasilitas->ViewCustomAttributes = "";

		// deskripsi
		$this->deskripsi->ViewValue = $this->deskripsi->CurrentValue;
		$this->deskripsi->ViewCustomAttributes = "";

			// foto_fasilitas
			$this->foto_fasilitas->LinkCustomAttributes = "";
			$this->foto_fasilitas->HrefValue = "";
			$this->foto_fasilitas->HrefValue2 = $this->foto_fasilitas->UploadPath . $this->foto_fasilitas->Upload->DbValue;
			$this->foto_fasilitas->TooltipValue = "";

			// nama_fasilitas
			$this->nama_fasilitas->LinkCustomAttributes = "";
			$this->nama_fasilitas->HrefValue = "";
			$this->nama_fasilitas->TooltipValue = "";

			// deskripsi
			$this->deskripsi->LinkCustomAttributes = "";
			$this->deskripsi->HrefValue = "";
			$this->deskripsi->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// foto_fasilitas
			$this->foto_fasilitas->EditAttrs["class"] = "form-control";
			$this->foto_fasilitas->EditCustomAttributes = "";
			if (!ew_Empty($this->foto_fasilitas->Upload->DbValue)) {
				$this->foto_fasilitas->EditValue = $this->foto_fasilitas->Upload->DbValue;
			} else {
				$this->foto_fasilitas->EditValue = "";
			}
			if (!ew_Empty($this->foto_fasilitas->CurrentValue))
					$this->foto_fasilitas->Upload->FileName = $this->foto_fasilitas->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->foto_fasilitas);

			// nama_fasilitas
			$this->nama_fasilitas->EditAttrs["class"] = "form-control";
			$this->nama_fasilitas->EditCustomAttributes = "";
			$this->nama_fasilitas->EditValue = ew_HtmlEncode($this->nama_fasilitas->CurrentValue);
			$this->nama_fasilitas->PlaceHolder = ew_RemoveHtml($this->nama_fasilitas->FldCaption());

			// deskripsi
			$this->deskripsi->EditAttrs["class"] = "form-control";
			$this->deskripsi->EditCustomAttributes = "";
			$this->deskripsi->EditValue = ew_HtmlEncode($this->deskripsi->CurrentValue);
			$this->deskripsi->PlaceHolder = ew_RemoveHtml($this->deskripsi->FldCaption());

			// Add refer script
			// foto_fasilitas

			$this->foto_fasilitas->LinkCustomAttributes = "";
			$this->foto_fasilitas->HrefValue = "";
			$this->foto_fasilitas->HrefValue2 = $this->foto_fasilitas->UploadPath . $this->foto_fasilitas->Upload->DbValue;

			// nama_fasilitas
			$this->nama_fasilitas->LinkCustomAttributes = "";
			$this->nama_fasilitas->HrefValue = "";

			// deskripsi
			$this->deskripsi->LinkCustomAttributes = "";
			$this->deskripsi->HrefValue = "";
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
		if ($this->foto_fasilitas->Upload->FileName == "" && !$this->foto_fasilitas->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->foto_fasilitas->FldCaption(), $this->foto_fasilitas->ReqErrMsg));
		}
		if (!$this->nama_fasilitas->FldIsDetailKey && !is_null($this->nama_fasilitas->FormValue) && $this->nama_fasilitas->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nama_fasilitas->FldCaption(), $this->nama_fasilitas->ReqErrMsg));
		}
		if (!$this->deskripsi->FldIsDetailKey && !is_null($this->deskripsi->FormValue) && $this->deskripsi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->deskripsi->FldCaption(), $this->deskripsi->ReqErrMsg));
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

		// foto_fasilitas
		if ($this->foto_fasilitas->Visible && !$this->foto_fasilitas->Upload->KeepFile) {
			$this->foto_fasilitas->Upload->DbValue = ""; // No need to delete old file
			if ($this->foto_fasilitas->Upload->FileName == "") {
				$rsnew['foto_fasilitas'] = NULL;
			} else {
				$rsnew['foto_fasilitas'] = $this->foto_fasilitas->Upload->FileName;
			}
		}

		// nama_fasilitas
		$this->nama_fasilitas->SetDbValueDef($rsnew, $this->nama_fasilitas->CurrentValue, "", FALSE);

		// deskripsi
		$this->deskripsi->SetDbValueDef($rsnew, $this->deskripsi->CurrentValue, "", FALSE);
		if ($this->foto_fasilitas->Visible && !$this->foto_fasilitas->Upload->KeepFile) {
			$OldFiles = ew_Empty($this->foto_fasilitas->Upload->DbValue) ? array() : array($this->foto_fasilitas->Upload->DbValue);
			if (!ew_Empty($this->foto_fasilitas->Upload->FileName)) {
				$NewFiles = array($this->foto_fasilitas->Upload->FileName);
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->foto_fasilitas->Upload->Index < 0) ? $this->foto_fasilitas->FldVar : substr($this->foto_fasilitas->FldVar, 0, 1) . $this->foto_fasilitas->Upload->Index . substr($this->foto_fasilitas->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->foto_fasilitas->TblVar) . $file)) {
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
							$file1 = ew_UploadFileNameEx($this->foto_fasilitas->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->foto_fasilitas->TblVar) . $file1) || file_exists($this->foto_fasilitas->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->foto_fasilitas->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->foto_fasilitas->TblVar) . $file, ew_UploadTempPath($fldvar, $this->foto_fasilitas->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->foto_fasilitas->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->foto_fasilitas->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->foto_fasilitas->SetDbValueDef($rsnew, $this->foto_fasilitas->Upload->FileName, "", FALSE);
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
				if ($this->foto_fasilitas->Visible && !$this->foto_fasilitas->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->foto_fasilitas->Upload->DbValue) ? array() : array($this->foto_fasilitas->Upload->DbValue);
					if (!ew_Empty($this->foto_fasilitas->Upload->FileName)) {
						$NewFiles = array($this->foto_fasilitas->Upload->FileName);
						$NewFiles2 = array($rsnew['foto_fasilitas']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->foto_fasilitas->Upload->Index < 0) ? $this->foto_fasilitas->FldVar : substr($this->foto_fasilitas->FldVar, 0, 1) . $this->foto_fasilitas->Upload->Index . substr($this->foto_fasilitas->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->foto_fasilitas->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->foto_fasilitas->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
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
							@unlink($this->foto_fasilitas->OldPhysicalUploadPath() . $OldFiles[$i]);
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

		// foto_fasilitas
		ew_CleanUploadTempPath($this->foto_fasilitas, $this->foto_fasilitas->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("fasilitaslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($fasilitas_add)) $fasilitas_add = new cfasilitas_add();

// Page init
$fasilitas_add->Page_Init();

// Page main
$fasilitas_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$fasilitas_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ffasilitasadd = new ew_Form("ffasilitasadd", "add");

// Validate form
ffasilitasadd.Validate = function() {
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
			felm = this.GetElements("x" + infix + "_foto_fasilitas");
			elm = this.GetElements("fn_x" + infix + "_foto_fasilitas");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $fasilitas->foto_fasilitas->FldCaption(), $fasilitas->foto_fasilitas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nama_fasilitas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $fasilitas->nama_fasilitas->FldCaption(), $fasilitas->nama_fasilitas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_deskripsi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $fasilitas->deskripsi->FldCaption(), $fasilitas->deskripsi->ReqErrMsg)) ?>");

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
ffasilitasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
ffasilitasadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $fasilitas_add->ShowPageHeader(); ?>
<?php
$fasilitas_add->ShowMessage();
?>
<form name="ffasilitasadd" id="ffasilitasadd" class="<?php echo $fasilitas_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($fasilitas_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $fasilitas_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="fasilitas">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($fasilitas_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($fasilitas->foto_fasilitas->Visible) { // foto_fasilitas ?>
	<div id="r_foto_fasilitas" class="form-group">
		<label id="elh_fasilitas_foto_fasilitas" class="<?php echo $fasilitas_add->LeftColumnClass ?>"><?php echo $fasilitas->foto_fasilitas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $fasilitas_add->RightColumnClass ?>"><div<?php echo $fasilitas->foto_fasilitas->CellAttributes() ?>>
<span id="el_fasilitas_foto_fasilitas">
<div id="fd_x_foto_fasilitas">
<span title="<?php echo $fasilitas->foto_fasilitas->FldTitle() ? $fasilitas->foto_fasilitas->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($fasilitas->foto_fasilitas->ReadOnly || $fasilitas->foto_fasilitas->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="fasilitas" data-field="x_foto_fasilitas" name="x_foto_fasilitas" id="x_foto_fasilitas"<?php echo $fasilitas->foto_fasilitas->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_foto_fasilitas" id= "fn_x_foto_fasilitas" value="<?php echo $fasilitas->foto_fasilitas->Upload->FileName ?>">
<input type="hidden" name="fa_x_foto_fasilitas" id= "fa_x_foto_fasilitas" value="0">
<input type="hidden" name="fs_x_foto_fasilitas" id= "fs_x_foto_fasilitas" value="1000">
<input type="hidden" name="fx_x_foto_fasilitas" id= "fx_x_foto_fasilitas" value="<?php echo $fasilitas->foto_fasilitas->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_foto_fasilitas" id= "fm_x_foto_fasilitas" value="<?php echo $fasilitas->foto_fasilitas->UploadMaxFileSize ?>">
</div>
<table id="ft_x_foto_fasilitas" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $fasilitas->foto_fasilitas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fasilitas->nama_fasilitas->Visible) { // nama_fasilitas ?>
	<div id="r_nama_fasilitas" class="form-group">
		<label id="elh_fasilitas_nama_fasilitas" for="x_nama_fasilitas" class="<?php echo $fasilitas_add->LeftColumnClass ?>"><?php echo $fasilitas->nama_fasilitas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $fasilitas_add->RightColumnClass ?>"><div<?php echo $fasilitas->nama_fasilitas->CellAttributes() ?>>
<span id="el_fasilitas_nama_fasilitas">
<input type="text" data-table="fasilitas" data-field="x_nama_fasilitas" name="x_nama_fasilitas" id="x_nama_fasilitas" placeholder="<?php echo ew_HtmlEncode($fasilitas->nama_fasilitas->getPlaceHolder()) ?>" value="<?php echo $fasilitas->nama_fasilitas->EditValue ?>"<?php echo $fasilitas->nama_fasilitas->EditAttributes() ?>>
</span>
<?php echo $fasilitas->nama_fasilitas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fasilitas->deskripsi->Visible) { // deskripsi ?>
	<div id="r_deskripsi" class="form-group">
		<label id="elh_fasilitas_deskripsi" for="x_deskripsi" class="<?php echo $fasilitas_add->LeftColumnClass ?>"><?php echo $fasilitas->deskripsi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $fasilitas_add->RightColumnClass ?>"><div<?php echo $fasilitas->deskripsi->CellAttributes() ?>>
<span id="el_fasilitas_deskripsi">
<input type="text" data-table="fasilitas" data-field="x_deskripsi" name="x_deskripsi" id="x_deskripsi" placeholder="<?php echo ew_HtmlEncode($fasilitas->deskripsi->getPlaceHolder()) ?>" value="<?php echo $fasilitas->deskripsi->EditValue ?>"<?php echo $fasilitas->deskripsi->EditAttributes() ?>>
</span>
<?php echo $fasilitas->deskripsi->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$fasilitas_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $fasilitas_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $fasilitas_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
ffasilitasadd.Init();
</script>
<?php
$fasilitas_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fasilitas_add->Page_Terminate();
?>
