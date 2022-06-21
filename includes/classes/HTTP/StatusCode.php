<?php
namespace HTTP;

class StatusCode {
  // 1xx: Informational
  const HTTP_CONTINUE                     = 100;
  const HTTP_SWITCHING                    = 101;
  const HTTP_PROCESSING                   = 102;

  // 2xx: Success
  const HTTP_OK                           = 200;
  const HTTP_CREATED                      = 201;
  const HTTP_ACCEPTED                     = 202;
  const HTTP_NONAUTHORITATIVE_INFORMATION = 203;
  const HTTP_NOCONTENT                    = 204;
  const HTTP_RESETCONTENT                 = 205;
  const HTTP_PARTIALCONTENT               = 206;
  const HTTP_MULTISTATUS                  = 207;
  const HTTP_ALREADYREPORTED              = 208;
  const HTTP_IMUSED                       = 226;

  // 3xx: Redirection
  const HTTP_MULTIPLECHOICES              = 300;
  const HTTP_MOVEDPERMANENTLY             = 301;
  const HTTP_MOVEDTEMPORARILY             = 302;
  const HTTP_FOUND                        = 302;
  const HTTP_SEEOTHER                     = 303;
  const HTTP_NOTMODIFIED                  = 304;
  const HTTP_USEPROXY                     = 305;
  const HTTP_TEMPORARYREDIRECT            = 307;
  const HTTP_PERMANENTREDIRECT            = 308;

  // 4xx: Client Error
  const HTTP_BADREQUEST                   = 400;
  const HTTP_UNAUTHORIZED                 = 401;
  const HTTP_PAYMENTREQUIRED              = 402;
  const HTTP_FORBIDDEN                    = 403;
  const HTTP_NOTFOUND                     = 404;
  const HTTP_METHODNOTALLOWED             = 405;
  const HTTP_NOTACCEPTABLE                = 406;
  const HTTP_PROXYAUTHREQUIRED            = 407;
  const HTTP_REQUESTTIMEOUT               = 408;
  const HTTP_CONFLICT                     = 409;
  const HTTP_GONE                         = 410;
  const HTTP_LENGTHREQUIRED               = 411;
  const HTTP_PRECONDITIONFAILED           = 412;
  const HTTP_PAYLOADTOOLARGE              = 413;
  const HTTP_URITOOLONG                   = 414;
  const HTTP_UNSUPPORTEDMEDIATYPE         = 415;
  const HTTP_RANGENOTSATISFABLE           = 416;
  const HTTP_EXPECTATIONFAILED            = 417;
  const HTTP_IMATEAPOT                    = 418;
  const HTTP_MISDIRECTEDREQUEST           = 421;
  const HTTP_UNPROCESSABLEENTITY          = 422;
  const HTTP_LOCKED                       = 423;
  const HTTP_FAILEDDEPENDENCY             = 424;
  const HTTP_UPGRADEREQUIRED              = 426;
  const HTTP_PRECONDITIONREQUIRED         = 428;
  const HTTP_TOOMANYREQUESTS              = 429;
  const HTTP_REQUESTHEADERFIELDSTOOLARGE  = 431;
  const HTTP_RETRYWITH                    = 449;
  const HTTP_UNAVAILABLEFORLEGALREASONS   = 451;

  // 5xx: Server Error
  const HTTP_INTERNALSERVERERROR          = 500;
  const HTTP_NOTIMPLEMENTED               = 501;
  const HTTP_BADGATEWAY                   = 502;
  const HTTP_SERVICEUNAVAILABLE           = 503;
  const HTTP_GATEWAYTIMEOUT               = 504;
  const HTTP_VERSIONNOTSUPPORTED          = 505;
  const HTTP_VARIANTALSONEGOTIATES        = 506;
  const HTTP_INSUFFICIENTSTORAGE          = 507;
  const HTTP_LOOPDETECTED                 = 508;
  const HTTP_BANDWIDTHLIMITEXCEEDED       = 509;
  const HTTP_NOTEXTENDED                  = 510;
  const HTTP_NETWORKAUTHREQUIRED          = 511;
  const HTTP_UNKNOWNERROR                 = 520;
  const HTTP_WEBSERVERISDOWN              = 521;
  const HTTP_CONNECTIONTIMEDOUT           = 522;
  const HTTP_ORIGINISUNREACHABLE          = 523;
  const HTTP_TIMEOUTOCCURED               = 524;
  const HTTP_SSLHANDSHAKEFAILED           = 525;
  const HTTP_INVALIDSSLCERT               = 526;

  // 000: unknown
  const HTTP_UNKNOWNCODE                  = 000;
}