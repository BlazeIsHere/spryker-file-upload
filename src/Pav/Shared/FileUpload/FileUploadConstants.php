<?php

namespace Pav\Shared\FileUpload;

interface FileUploadConstants
{

    const UPLOAD_STORAGE = 'upload_storage';
    const CONFIG_TYPE = 'type';
    const CONFIG_CONFIG = 'config';

    const ADAPTER_LOCAL = 'local';
    const ADAPTER_S3 = 's3';

    const AWS_KEY = 'aws_key';
    const AWS_SECRET = 'aws_secret';
    const AWS_REGION = 'aws_region';
    const BUCKET_NAME = 'bucket_name';
    const AWS_CREDENTIALS = 'credentials';
    const KEY = 'key';
    const SECRET = 'secret';
    const REGION = 'region';
    const VERSION = 'version';
    const LATEST = 'latest';
    const BUCKET_PREFIX = 'bucket_prefix';
    const LOCAL_PATH = 'path';
    const BASE_URL = 'base_url';

}
