{
  "id":24,
  "name":"formbuilder_formrequests",
  "formtabs":[
    {
      "MIGX_id":1,
      "caption":"Request",
      "print_before_tabs":"0",
      "fields":[
        {
          "MIGX_id":1,
          "field":"",
          "caption":"",
          "description":"[[!bloX? \n&packagename=`migxformbuilder`\n&component=`migxformbuilder`\n&project=`migxformbuilder`\n&task=`viewformrequest`\n&form_id=`[[+form_id]]`\n&object_id=`[[+id]]`\n]]\n",
          "description_is_code":1,
          "inputTV":"",
          "inputTVtype":"",
          "configs":"",
          "sourceFrom":"config",
          "sources":"[]",
          "inputOptionValues":"",
          "default":""
        }
      ]
    }
  ],
  "contextmenus":"",
  "actionbuttons":"toggletrash||exportview",
  "columnbuttons":"recall_remove_delete||viewdetails||printdetails",
  "filters":[
    {
      "MIGX_id":1,
      "name":"formid",
      "label":"formid",
      "emptytext":"filter formid",
      "type":"combobox",
      "getlistwhere":{
        "form_id":"[[+formid]]"
      },
      "getcomboprocessor":"getformcombo",
      "combotextfield":"name",
      "comboidfield":"id",
      "comboparent":"",
      "default":""
    }
  ],
  "extended":{
    "migx_add":"",
    "formcaption":"",
    "update_win_title":"",
    "win_id":"formbuilder_formrequests",
    "maxRecords":"",
    "multiple_formtabs":"",
    "extrahandlers":"",
    "packageName":"migxformbuilder",
    "classname":"mfbFormRequest",
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
    "has_jointable":"yes",
    "getlistwhere":"",
    "joins":[
      {
        "alias":"Form"
      }
    ],
    "cmpmaincaption":"",
    "cmptabcaption":"Form Requests",
    "cmptabdescription":"",
    "cmptabcontroller":"",
    "winbuttons":"",
    "onsubmitsuccess":"",
    "submitparams":""
  },
  "columns":[
    {
      "MIGX_id":1,
      "header":"ID",
      "dataIndex":"id",
      "width":10,
      "sortable":true,
      "show_in_grid":1,
      "renderer":"",
      "clickaction":"",
      "selectorconfig":"",
      "renderoptions":"[]"
    },
    {
      "MIGX_id":3,
      "header":"Formname",
      "dataIndex":"Form_name",
      "width":20,
      "sortable":true,
      "show_in_grid":1,
      "renderer":"this.renderRowActions",
      "clickaction":"",
      "selectorconfig":"",
      "renderoptions":"[]"
    },
    {
      "MIGX_id":2,
      "header":"Email",
      "dataIndex":"email",
      "width":20,
      "sortable":"false",
      "show_in_grid":1,
      "renderer":"",
      "clickaction":"",
      "selectorconfig":"",
      "renderoptions":"[]"
    },
    {
      "MIGX_id":4,
      "header":"Firstname",
      "dataIndex":"firstname",
      "width":20,
      "sortable":"false",
      "show_in_grid":1,
      "renderer":"",
      "clickaction":"",
      "selectorconfig":"",
      "renderoptions":"[]"
    },
    {
      "MIGX_id":5,
      "header":"Lastname",
      "dataIndex":"lastname",
      "width":20,
      "sortable":"false",
      "show_in_grid":1,
      "renderer":"",
      "clickaction":"",
      "selectorconfig":"",
      "renderoptions":"[]"
    },
    {
      "MIGX_id":6,
      "header":"Created On",
      "dataIndex":"createdon",
      "width":10,
      "sortable":true,
      "show_in_grid":1,
      "renderer":"this.renderDate",
      "clickaction":"",
      "selectorconfig":"",
      "renderoptions":"[]"
    },
    {
      "MIGX_id":7,
      "header":"",
      "dataIndex":"deleted",
      "width":"",
      "sortable":"false",
      "show_in_grid":"0",
      "renderer":"",
      "clickaction":"",
      "selectorconfig":"",
      "renderoptions":"[]"
    }
  ],
  "createdby":1,
  "createdon":"2013-05-03 05:26:46",
  "editedby":2,
  "editedon":"2013-07-22 06:52:48",
  "deleted":0,
  "deletedon":"-1-11-30 00:00:00",
  "deletedby":0,
  "published":1,
  "publishedon":"2013-05-03 01:00:00",
  "publishedby":0
}