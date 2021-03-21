<?php

return [
    'SEARCH_DATA_NOT_FOUND' => [
        'code' => 199,
        'httpCode' => 200,
        'message' => '查無資料'
    ],
    'OK' => [
        'code' => 200,
        'httpCode' => 200,
        'message' => '成功'
    ],
    'CREATED' => [
        'code' => 201,
        'httpCode' => 200,
        'message' => '新增成功'
    ],
    'UPDATED' => [
        'code' => 202,
        'httpCode' => 200,
        'message' => '更新成功'
    ],
    'DELETED' => [
        'code' => 203,
        'httpCode' => 200,
        'message' => '刪除成功'
    ],

    'ERR_PARAMETER' => [
        'code' => 4001,
        'httpCode' => 400,
        'message' => '缺少必要參數'
    ],
    'ERR_DATABASE' => [
        'code' => 4002,
        'httpCode' => 400,
        'message' => '資料庫操作異常'
    ],
    'ERR_CREATE_FAIL' => [
        'code' => 4003,
        'httpCode' => 400,
        'message' => '資料新增失敗'
    ],
    'ERR_UPDATE_FAIL' => [
        'code' => 4004,
        'httpCode' => 400,
        'message' => '資料更新失敗'
    ],
    'ERR_DATA_NOT_EXISTS' => [
        'code' => 4005,
        'httpCode' => 400,
        'message' => '查無對應資料'
    ],
    'ERR_DELETE_FAIL' => [
        'code' => 4006,
        'httpCode' => 400,
        'message' => '資料刪除失敗'
    ],
    'ERR_OTHER_EDIT_THIS_DATA' => [
        'code' => 4007,
        'httpCode' => 400,
        'message' => '其他使用者編輯中'
    ],
    'ERR_UNKNOW' => [
        'code' => 4008,
        'httpCode' => 400,
        'message' => '未知的錯誤'
    ],
    'ERR_DATA_LIMIT' => [
        'code' => 4009,
        'httpCode' => 400,
        'message' => '超出資料上限'
    ],
    'ERR_UPLOAD_FILE_FAIL' => [
        'code' => 4010,
        'httpCode' => 400,
        'message' => '檔案上傳失敗'
    ],
    'ERR_DATA_DUPLICATE' => [
        'code' => 4011,
        'httpCode' => 400,
        'message' => '資料重覆'
    ],
    'ERR_ACTION_DUPLICATE' => [
        'code' => 4012,
        'httpCode' => 400,
        'message' => '不可重覆操作'
    ],
    'ERR_FORBIDDEN' => [
        'code' => 403,
        'httpCode' => 403,
        'message' => 'Forbidden'
    ],
    'ERR_SYMBOL_NOT_FOUND' => [
        'code' => 404,
        'httpCode' => 404,
        'message' => 'Symbol Not Found'
    ],
    'ERR_RESOURCE_NOT_FOUND' => [
        'code' => 404,
        'httpCode' => 404,
        'message' => 'Resource Not Found'
    ],
    'ERR_METHOD_NOT_ALLOWED' => [
        'code' => 405,
        'httpCode' => 405,
        'message' => 'Method Not Allowed'
    ],
    'ERR_SERVER_INTERNAL' => [
        'code' => 500,
        'httpCode' => 500,
        'message' => 'Internal Error'
    ],
    'ERR_UNAUTHORIZED' => [
        'code' => 401,
        'httpCode' => 401,
        'message' => 'Token Unauthorized'
    ],
    'ERR_TOKEN_EXPIRED' => [
        'code' => 4013,
        'httpCode' => 401,
        'message' => 'Token Expired'
    ],
    'ERR_REGISTER_BY_OTHER_WAY' => [
        'code' => 409,
        'httpCode' => 409,
        'message' => 'Already Register By Other Way',
    ],


    'ERR_BINDING_FAILED' => [
        'code' => 40913,
        'httpCode' => 409,
        'message' => '帳號已綁定 Email 登入'
    ],

    'ERR_DEPENDENCY_SERVICE_ERROR' => [
        'code' => 503,
        'httpCode' => 503,
        'message' => 'Service Unavailable'
    ],

    'ERR_VALIDATION_FAILED' => [
        'code' => 422,
        'httpCode' => 422,
        'message' => '參數無效'
    ],

    'INCOMPLETE_IOS_REGISTER' => [
        'code' => 20041,
        'httpCode' => 200,
        'message' => '合法 Apple Id，尚未新增 Email 登入'
    ],

    'INACTIVATE_EMAIL_IOS' => [
        'code' => 20042,
        'httpCode' => 200,
        'message' => '合法 Apple id，Email 等待驗證'
    ],
];
