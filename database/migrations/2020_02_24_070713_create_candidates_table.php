<?php
/**
 * Migration genrated using LaraAdmin
 * Help: Contact Sagar Upadhyay (usagar80@gmail.com)
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Dwij\Laraadmin\Models\Module;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Candidates", 'candidates', 'name', 'fa-user-md', [
            ["name", "Name", "String", false, "", 1, 255, true],
            ["city", "City", "Dropdown", false, "", 0, 0, true, "@cities"],
            ["native_place", "Native Place", "Dropdown", false, "", 0, 0, true, "@cities"],
            ["crnnt_desgnation", "Current Designation", "String", false, "", 1, 255, true],
            ["report_to", "Reporting To", "String", false, "", 1, 255, true],
            ["total_experience", "Total Experience", "Dropdown", false, "", 0, 0, false, "@experiences"],
            ["qualification_ug", "Qualification (UG)", "Dropdown", false, "", 0, 0, false, "@qualification_ugs"],
            ["qualification_pg", "Qualification (PG)", "Dropdown", false, "", 0, 0, false, "@qualification_pgs"],
            ["crrnt_ctc", "Current CTC", "Currency", false, "", 1, 1000000000, true],
            ["expected_ctc", "Expected CTC", "Currency", false, "", 1, 4294967295, true],
            ["notice_period", "Notice Period", "Dropdown", false, "", 0, 0, true, ["1 Month","2 Month","3 Month","7 Days","15 Days","More than 3 Month"]],
            ["notice_buy_out", "Notice Buy Out", "Radio", false, "", 0, 0, true, ["Yes","No","Not Sure"]],
            ["age", "Age", "Integer", false, "18", 18, 100, true],
            ["family_detail", "Family Detail", "Textarea", false, "", 0, 0, false],
            ["recruitor", "Recruitor Note", "Address", false, "", 0, 256, false],
            ["recruitor_note", "Recruitor Note", "Textarea", false, "", 0, 0, false],
            ["mobile_1", "Mobile 1", "Mobile", false, "", 0, 20, true],
            ["mobile_2", "Mobile 2", "Mobile", false, "", 0, 20, false],
            ["email_1", "Email 1", "Email", false, "", 0, 256, true],
            ["email_2", "Email 2", "Email", false, "", 0, 256, false],
            ["skype", "Skype", "String", false, "", 0, 256, false],
            ["remark", "Remark", "Textarea", false, "", 0, 0, false],
            ["resume", "Resume", "File", false, "", 0, 0, false],
            ["created_by", "Created By", "Dropdown", false, "", 0, 0, false, "@employees"],
            ["last_edited_by", "Last Edited By", "Dropdown", false, "", 0, 0, false, "@employees"],
        ]);
		
		/*
		Row Format:
		["field_name_db", "Label", "UI Type", "Unique", "Default_Value", "min_length", "max_length", "Required", "Pop_values"]
        Module::generate("Module_Name", "Table_Name", "view_column_name" "Fields_Array");
        
		Module::generate("Books", 'books', 'name', [
            ["address",     "Address",      "Address",  false, "",          0,  1000,   true],
            ["restricted",  "Restricted",   "Checkbox", false, false,       0,  0,      false],
            ["price",       "Price",        "Currency", false, 0.0,         0,  0,      true],
            ["date_release", "Date of Release", "Date", false, "date('Y-m-d')", 0, 0,   false],
            ["time_started", "Start Time",  "Datetime", false, "date('Y-m-d H:i:s')", 0, 0, false],
            ["weight",      "Weight",       "Decimal",  false, 0.0,         0,  20,     true],
            ["publisher",   "Publisher",    "Dropdown", false, "Marvel",    0,  0,      false, ["Bloomsbury","Marvel","Universal"]],
            ["publisher",   "Publisher",    "Dropdown", false, 3,           0,  0,      false, "@publishers"],
            ["email",       "Email",        "Email",    false, "",          0,  0,      false],
            ["file",        "File",         "File",     false, "",          0,  1,      false],
            ["files",       "Files",        "Files",    false, "",          0,  10,     false],
            ["weight",      "Weight",       "Float",    false, 0.0,         0,  20.00,  true],
            ["biography",   "Biography",    "HTML",     false, "<p>This is description</p>", 0, 0, true],
            ["profile_image", "Profile Image", "Image", false, "img_path.jpg", 0, 250,  false],
            ["pages",       "Pages",        "Integer",  false, 0,           0,  5000,   false],
            ["mobile",      "Mobile",       "Mobile",   false, "+91  8888888888", 0, 20,false],
            ["media_type",  "Media Type",   "Multiselect", false, ["Audiobook"], 0, 0,  false, ["Print","Audiobook","E-book"]],
            ["media_type",  "Media Type",   "Multiselect", false, [2,3],    0,  0,      false, "@media_types"],
            ["name",        "Name",         "Name",     false, "John Doe",  5,  250,    true],
            ["password",    "Password",     "Password", false, "",          6,  250,    true],
            ["status",      "Status",       "Radio",    false, "Published", 0,  0,      false, ["Draft","Published","Unpublished"]],
            ["author",      "Author",       "String",   false, "JRR Tolkien", 0, 250,   true],
            ["genre",       "Genre",        "Taginput", false, ["Fantacy","Adventure"], 0, 0, false],
            ["description", "Description",  "Textarea", false, "",          0,  1000,   false],
            ["short_intro", "Introduction", "TextField",false, "",          5,  250,    true],
            ["website",     "Website",      "URL",      false, "http://dwij.in", 0, 0,  false],
        ]);
		*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('candidates')) {
            Schema::drop('candidates');
        }
    }
}
