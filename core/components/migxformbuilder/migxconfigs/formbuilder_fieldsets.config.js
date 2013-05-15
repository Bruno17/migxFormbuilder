{
  "id":78,
  "name":"formbuilder_fieldsets",
  "formtabs":[
    {
      "MIGX_id":1,
      "caption":"Fieldset",
      "print_before_tabs":"0",
      "fields":[
        {
          "MIGX_id":1,
          "field":"name",
          "caption":"Fieldset Name",
          "description":"",
          "description_is_code":"0",
          "inputTV":"",
          "inputTVtype":"",
          "configs":"",
          "sourceFrom":"config",
          "sources":"[]",
          "inputOptionValues":"",
          "default":""
        },
        {
          "MIGX_id":2,
          "field":"legend",
          "caption":"Legend",
          "inputTV":"",
          "inputTVtype":"",
          "configs":"",
          "sourceFrom":"config",
          "sources":"",
          "inputOptionValues":"",
          "default":""
        },
        {
          "MIGX_id":3,
          "field":"hidden",
          "caption":"Hidden",
          "description":"hide this fieldset",
          "description_is_code":"0",
          "inputTV":"",
          "inputTVtype":"checkbox",
          "configs":"",
          "sourceFrom":"config",
          "sources":"[]",
          "inputOptionValues":"yes==1",
          "default":""
        }
      ]
    },
    {
      "MIGX_id":2,
      "caption":"Fields",
      "print_before_tabs":"0",
      "fields":[
        {
          "MIGX_id":3,
          "field":"fields",
          "caption":"Fields",
          "description":"DB - fields are title,firstname,lastname,email,phone,address,zip,message - additional fields are also possible",
          "description_is_code":"0",
          "inputTV":"",
          "inputTVtype":"migx",
          "configs":"formbuilder_fields:migxformbuilder",
          "sourceFrom":"config",
          "sources":"[]",
          "inputOptionValues":"",
          "default":""
        }
      ]
    }
  ],
  "contextmenus":"",
  "actionbuttons":"",
  "columnbuttons":"",
  "filters":"[]",
  "extended":{
    "migx_add":"Create Fieldset",
    "formcaption":"Fieldset",
    "update_win_title":"Formbuilder Fieldset",
    "win_id":"formbuilder_fieldsets",
    "maxRecords":"",
    "multiple_formtabs":"",
    "extrahandlers":"",
    "packageName":"migxformbuilder",
    "classname":"",
    "task":"",
    "getlistsort":"",
    "getlistsortdir":"",
    "use_custom_prefix":"0",
    "prefix":"",
    "grid":"",
    "gridload_mode":1,
    "check_resid":1,
    "check_resid_TV":"",
    "join_alias":"",
    "getlistwhere":"",
    "joins":"",
    "cmpmaincaption":"",
    "cmptabcaption":"",
    "cmptabdescription":"",
    "cmptabcontroller":""
  },
  "columns":[
    {
      "MIGX_id":1,
      "header":"Name",
      "dataIndex":"name",
      "width":20,
      "renderer":"",
      "sortable":"false",
      "show_in_grid":1
    },
    {
      "MIGX_id":2,
      "header":"Legend",
      "dataIndex":"legend",
      "width":30,
      "renderer":"",
      "sortable":"false",
      "show_in_grid":1
    }
  ],
  "createdby":2,
  "createdon":"2012-09-16 22:35:54",
  "editedby":1,
  "editedon":"2013-05-14 16:09:43",
  "deleted":0,
  "deletedon":"-1-11-30 00:00:00",
  "deletedby":0,
  "published":1,
  "publishedon":"2013-04-29 01:00:00",
  "publishedby":0
}