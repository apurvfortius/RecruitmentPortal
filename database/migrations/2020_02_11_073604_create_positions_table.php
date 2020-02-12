<?php
/**
 * Migration genrated using LaraAdmin
 * Help: Contact Sagar Upadhyay (usagar80@gmail.com)
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Dwij\Laraadmin\Models\Module;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Positions", 'positions', 'title', 'fa-bolt', [
            ["position_code", "Position Code", "String", true, "", 1, 50, true],
            ["company_id", "Company", "Dropdown", false, "", 0, 0, true, "@organizations"],
            ["title", "Name", "String", false, "", 1, 255, true],
            ["position_level", "Level", "Dropdown", false, "", 0, 0, true, "@position_levels"],
            ["industry_id", "Industry", "Dropdown", false, "", 0, 0, true, "@industries"],
            ["department_id", "Department", "Dropdown", false, "", 0, 0, true, "@departments"],
            ["sub_department_id", "Sub Department", "Dropdown", false, "", 0, 0, false, "@sub_departments"],
            ["report_to", "Report To", "String", false, "", 1, 255, false],
            ["team_size", "Approx Team Size", "String", false, "", 1, 255, false],
            ["location", "Location", "Dropdown", false, "", 0, 0, true, "@cities"],
            ["budget_id", "Budget", "Dropdown", false, "", 0, 0, true, "@budgets"],
            ["qualification_ug", "Qualification (UG)", "Dropdown", false, "", 0, 0, true, "@qualification_ugs"],
            ["qualification_pg", "Qualification (PG)", "Dropdown", false, "", 0, 0, true, "@qualification_pgs"],
            ["no_position", "No. of Positions", "Integer", false, "1", 0, 11, true],
            ["req_exp_id", "Required Experience", "Dropdown", false, "", 0, 0, true, "@experiences"],
            ["urgency_pos", "Urgency of Position", "Dropdown", false, "", 0, 0, true, ["Yes","No"]],
            ["buy_out", "Buy Out", "Radio", false, "", 0, 0, true, ["Yes","No"]],
            ["com_turnover", "Company Turnover", "Dropdown", false, "", 0, 0, false, ["100000","200000"]],
            ["emp_strength", "Employee Strength", "Dropdown", false, "", 0, 0, false, ["0 to 25","26 to 100","100 to 250","251 to 500"]],
            ["jd_available", "JD Available", "Radio", false, "", 0, 0, false, ["Yes","No"]],
            ["website", "Website", "URL", false, "", 0, 256, true],
            ["pos_date", "Postion Date", "Date", false, "", 0, 0, true],
            ["job_description", "Job Desciption", "Textarea", false, "", 0, 0, false],
            ["pos_given_by", "Position Given By", "String", false, "", 1, 255, true],
            ["pos_assign_to", "Position Assigned To", "Dropdown", false, "", 0, 0, true, "@employees"],
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
        if (Schema::hasTable('positions')) {
            Schema::drop('positions');
        }
    }
}
