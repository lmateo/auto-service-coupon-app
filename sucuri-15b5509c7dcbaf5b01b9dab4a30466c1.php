

<?php
/* Sucuri integrity monitor
 * Integrity checking and server side scanning.
 *
 * Copyright (C) 2010, 2011, 2012 Sucuri, LLC
 * http://sucuri.net
 * Do not distribute or share.
 */

$MYMONITOR = "monitor14";
$my_sucuri_encoding = "



LyogU3VjdXJpIGludGVncml0eSBtb25pdG9yIC4gCiAqIENvbm5lY3RzIGJhY2sgaG9tZS4KICog
Q29weXJpZ2h0IChDKSAyMDEwLTIwMTggU3VjdXJpLCBMTEMKICogRG8gbm90IGRpc3RyaWJ1dGUg
b3Igc2hhcmUuCiAqLwoKCiRTVUNVUklQV0QgPSAiNTM1ZTI2ZWIwZjMzMjRlODA1NmZlNjk1NDk3
NmIwNGM2MmViOTc3ZjJjYzUxIjsKCgppZihpc3NldCgkX0dFVFsndGVzdCddKSkKewogICAgZWNo
byAiT0s6IFN1Y3VyaTogRm91bmRcbiI7CiAgICBleGl0KDApOwp9CgoKCi8qIFZhbGlkIGFyZ3Vt
ZW50LiAqLwppZighaXNzZXQoJF9HRVRbJ3J1biddKSkKewogICAgZXhpdCgwKTsKfQoKCi8qIE11
c3QgaGF2ZSBhbiBJUCBhZGRyZXNzLiAqLwppZighaXNzZXQoJF9TRVJWRVJbJ1JFTU9URV9BRERS
J10pKQp7CiAgICBleGl0KDApOwp9Cgokb3JpZ3JlbW90ZWlwID0gJF9TRVJWRVJbJ1JFTU9URV9B
RERSJ107CgovKiBJZiBjb21pbmcgZnJvbSBjbG91ZGZsYXJlOiAqLwppZihpc3NldCgkX1NFUlZF
UlsnSFRUUF9DRl9DT05ORUNUSU5HX0lQJ10pICYmICRfU0VSVkVSWydSRU1PVEVfQUREUiddICE9
PSAkX1NFUlZFUlsnSFRUUF9DRl9DT05ORUNUSU5HX0lQJ10pCnsKICAgICRfU0VSVkVSWydSRU1P
VEVfQUREUiddID0gJF9TRVJWRVJbJ0hUVFBfQ0ZfQ09OTkVDVElOR19JUCddOwp9Ci8qIENsb3Vk
UHJveHkgaGVhZGVycy4gKi8KZWxzZSBpZihpc3NldCgkX1NFUlZFUlsnSFRUUF9YX1NVQ1VSSV9D
TElFTlRJUCddKSkKewogICAgJF9TRVJWRVJbJ1JFTU9URV9BRERSJ10gPSAkX1NFUlZFUlsnSFRU
UF9YX1NVQ1VSSV9DTElFTlRJUCddOwp9Ci8qIE1vcmUgZ2F0ZXdheSByZXF1ZXN0cy4gKi8KZWxz
ZSBpZihpc3NldCgkX1NFUlZFUlsnSFRUUF9YX09SSUdfQ0xJRU5UX0lQJ10pKQp7CiAgICAkX1NF
UlZFUlsnUkVNT1RFX0FERFInXSA9ICRfU0VSVkVSWydIVFRQX1hfT1JJR19DTElFTlRfSVAnXTsK
fQplbHNlIGlmKGlzc2V0KCRfU0VSVkVSWydIVFRQX0NMSUVOVF9JUCddKSkKewogICAgJF9TRVJW
RVJbJ1JFTU9URV9BRERSJ10gPSAkX1NFUlZFUlsnSFRUUF9DTElFTlRfSVAnXTsKfQovKiBQcm94
eSByZXF1ZXN0cy4gKi8KZWxzZSBpZihpc3NldCgkX1NFUlZFUlsnSFRUUF9UUlVFX0NMSUVOVF9J
UCddKSkKewogICAgJF9TRVJWRVJbJ1JFTU9URV9BRERSJ10gPSAkX1NFUlZFUlsnSFRUUF9UUlVF
X0NMSUVOVF9JUCddOwp9Ci8qIFByb3h5IHJlcXVlc3RzLiAqLwplbHNlIGlmKGlzc2V0KCRfU0VS
VkVSWydIVFRQX1hfUkVBTF9JUCddKSkKewogICAgJF9TRVJWRVJbJ1JFTU9URV9BRERSJ10gPSAk
X1NFUlZFUlsnSFRUUF9YX1JFQUxfSVAnXTsKfQovKiBNb3JlIGdhdGV3YXkgcmVxdWVzdHMuICov
CmVsc2UgaWYoaXNzZXQoJF9TRVJWRVJbJ0hUVFBfWF9GT1JXQVJERURfRk9SJ10pKQp7CiAgICAk
X1NFUlZFUlsnUkVNT1RFX0FERFInXSA9ICRfU0VSVkVSWydIVFRQX1hfRk9SV0FSREVEX0ZPUidd
Owp9CgoKJG15aXBsaXN0ID0gYXJyYXkoCic2OS4xNjQuMjExLjM3JywKJzcyLjE0LjE4Ny41OCcs
Cic2OS4xNjQuMTk2LjUzJywKJzUwLjExNi40Ny4xODEnLAonNjYuMjI4LjM0LjQ5JywKJzY2LjIy
OC40MC4xODUnLAonNTAuMTE2LjM2LjkyJywKJzUwLjExNi4zNi45MycsCic1MC4xMTYuMy4xNzEn
LAonMTk4LjU4Ljk2LjIxMicsCic1MC4xMTYuNjMuMjIxJywKJzE5Mi4xNTUuOTIuMTEyJywKJzE5
Mi44MS4xMjguMzEnLAonMTk4LjU4LjEwNi4yNDQnLAonMTkyLjE1NS45NS4xMzknLAonMjMuMjM5
LjkuMjI3JywKJzE5OC41OC4xMTIuMTAzJywKJzE5Mi4xNTUuOTQuNDMnLAonMTYyLjIxNi4xNi4z
MycsCicxMDQuMjM3LjE0My4yNDInLAonMTA0LjIzNy4xMzkuMjI3JywKJzQ1LjMzLjc2LjE3JywK
JzQ1Ljc5LjIxMC41NycsCicxNzMuMjMwLjEzMy4xNjQnLAonNjkuMTY0LjIxOS40NScsCic0NS43
OS4yMDcuMTI3JywKJzM1LjIyNi42OC4xMjknLAonOTYuMTI2LjExNy4yMCcsCicxMDQuMjM3LjEz
OC4xNjgnLAonNDUuMzMuOTkuODgnLAonNjYuMjI4LjQ1LjE5NycsCic2Ni4yMjguNTIuNzUnLAon
NDUuNzkuMTYzLjY5JywKJzQ1Ljc5LjEuMTc3JywKJzQ1Ljc5LjE5OS43NicsCic0NS43OS45LjIw
OScsCic0NS43OS4yMTUuNTEnLAonNDUuNTYuMTExLjE3NicsCicyMy4yMzkuMzEuMTQ0JywKJzQ1
LjU2LjExOC4yMjgnLAonNDUuMzMuMjQuNTMnLAonNjYuMTc1LjIyMC4xMDcnLAonNDUuNTYuMTEx
LjE3NicsCicyMy4yMzkuMzEuMTQ0JywKJzQ1LjU2LjExOC4yMjgnLAonNDUuNTYuOTAuMTIyJywK
CicyNjAwOjNjMDA6OmYwM2M6OTFmZjpmZWFlOmUxMDQnLAonMjYwMDozYzAwOjpmMDNjOjkxZmY6
ZmU4NDplMjc1JywKJzI2MDA6M2MwMzo6ZjAzYzo5MWZmOmZlZTQ6YzlmMCcsCicyNjAwOjNjMDI6
OmYwM2M6OTFmZjpmZWU0OmM5OTgnLAonMjYwMDozYzAwOjpmMDNjOjkxZmY6ZmU4NDplMjE4JywK
JzI2MDA6M2MwMjo6ZjAzYzo5MWZmOmZlZGY6NThjNicsCicyNjAwOjNjMDI6OmYwM2M6OTFmZjpm
ZWRmOjU4MzUnLAonMjYwMDozYzAzOjpmMDNjOjkxZmY6ZmVkZjo2YTdhJywKJzI2MDA6M2MwMzo6
ZjAzYzo5MWZmOmZlNzA6MzZjZScsCicyNjAwOjNjMDI6OmYwM2M6OTFmZjpmZTcwOmYxMmQnLAon
MjYwMDozYzAxOjpmMDNjOjkxZmY6ZmU3MDo1MmJiJywKIjI2MDA6M2MwMjo6ZjAzYzo5MWZmOmZl
Njk6NGI2NiIsCiIyNjAwOjNjMDA6OmYwM2M6OTFmZjpmZTcwOjUyMTMiLAoiMjYwMDozYzAzOjpm
MDNjOjkxZmY6ZmVkYjpiOWNlIiwKIjI2MDA6M2MwMDo6ZjAzYzo5MWZmOmZlNmU6YTA0NiIsCiIy
NjAwOjNjMDI6OmYwM2M6OTFmZjpmZTZlOmEwZGQiLAoiMjYwMDozYzAzOjpmMDNjOjkxZmY6ZmU2
ZTphMGFjIiwKIjI2MDA6M2MwMjo6ZjAzYzo5MWZmOmZlOWI6Y2NhYyIsCiIyNjAwOjNjMDM6OmYw
M2M6OTFmZjpmZTU5OmYxZiIsCiIyNjAwOjNjMDI6OmYwM2M6OTFmZjpmZTU5OmZiYiIsIAoiMjYw
MDozYzAzOjpmMDNjOjkxZmY6ZmU1OTpmNTEiLAoiMjYwMDozYzAwOjpmMDNjOjkxZmY6ZmU1OTpm
ODQiLCAKJzI2MDA6M2MwMDo6ZjAzYzo5MWZmOmZlMWY6NzVjYicsCicyNjAwOjNjMDA6OmYwM2M6
OTFmZjpmZTFmOjc1N2MnLAonMjYwMDozYzAyOjpmMDNjOjkxZmY6ZmUxZjo3NTNjJywKJ2ZlODA6
OmZjZmQ6YWRmZjpmZWU2OjgwODcnLAonMjYwMDozYzAyOjpmMDNjOjkxZmY6ZmVlOTo2NGZhJywK
JzI2MDA6M2MwMzo6ZjAzYzo5MWZmOmZlZTk6NjQxNycsCik7CgoKJGlwbWF0Y2hlcyA9IDA7Cgpm
b3JlYWNoKCRteWlwbGlzdCBhcyAkbXlpcCkKewogICAgaWYoc3RycG9zKCRfU0VSVkVSWydSRU1P
VEVfQUREUiddLCAkbXlpcCkgIT09IEZBTFNFKQogICAgewogICAgICAgICRpcG1hdGNoZXMgPSAx
OwogICAgICAgIGJyZWFrOwogICAgfQogICAgaWYoc3RycG9zKCRvcmlncmVtb3RlaXAsICRteWlw
KSAhPT0gRkFMU0UpCiAgICB7CiAgICAgICAgJGlwbWF0Y2hlcyA9IDE7CiAgICAgICAgYnJlYWs7
CiAgICB9Cn0KCgppZigkaXBtYXRjaGVzID09IDApCnsKICAgIGVjaG8gIkVSUk9SOiBJbnZhbGlk
IElQIEFkZHJlc3NcbiI7CiAgICBleGl0KDApOwp9CgoKLyogVmFsaWQga2V5LiAqLwppZighaXNz
ZXQoJF9QT1NUWydzc2NyZWQnXSkpCnsKICAgIGVjaG8gIkVSUk9SOiBJbnZhbGlkIGFyZ3VtZW50
XG4iOwogICAgZXhpdCgwKTsKfQoKCi8qIENvbm5lY3QgYmFjayB0byBnZXQgd2hhdCB0byBydW4u
ICovCmlmKCFmdW5jdGlvbl9leGlzdHMoJ2N1cmxfZXhlYycpIHx8IGlzc2V0KCRfR0VUWydub2N1
cmwnXSkpCnsKICAgICRwb3N0ZGF0YSA9IGh0dHBfYnVpbGRfcXVlcnkoCiAgICAgICAgICAgIGFy
cmF5KAogICAgICAgICAgICAgICAgJ3AnID0+ICRTVUNVUklQV0QsCiAgICAgICAgICAgICAgICAn
cScgPT4gJF9QT1NUWydzc2NyZWQnXSwKICAgICAgICAgICAgICAgICkKICAgICAgICAgICAgKTsK
CiAgICAkb3B0cyA9IGFycmF5KCdodHRwJyA9PgogICAgICAgICAgICBhcnJheSgKICAgICAgICAg
ICAgICAgICdtZXRob2QnICA9PiAnUE9TVCcsCiAgICAgICAgICAgICAgICAnaGVhZGVyJyAgPT4g
IkNvbnRlbnQtdHlwZTogYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkXG5BY2NlcHQ6
ICovKiIsCiAgICAgICAgICAgICAgICAnZm9sbG93X2xvY2F0aW9uJyA9PiAwLAogICAgICAgICAg
ICAgICAgJ2NvbnRlbnQnID0+ICRwb3N0ZGF0YQogICAgICAgICAgICAgICAgKQogICAgICAgICAg
ICApOwoKICAgICRjb250ZXh0ID0gc3RyZWFtX2NvbnRleHRfY3JlYXRlKCRvcHRzKTsKICAgICRt
eV9zdWN1cmlfZW5jb2RpbmcgPSBmaWxlX2dldF9jb250ZW50cygiaHR0cHM6Ly8kTVlNT05JVE9S
LnN1Y3VyaS5uZXQvaW1vbml0b3IiLCBmYWxzZSwgJGNvbnRleHQpOwoKICAgIGlmKHN0cm5jbXAo
JG15X3N1Y3VyaV9lbmNvZGluZywgIldPUktFRDoiLDcpID09IDApCiAgICB7CiAgICB9CiAgICBl
bHNlCiAgICB7CiAgICAgICAgZWNobyAiRVJST1I6IFVuYWJsZSB0byBjb21wbGV0ZSAobWlzc2lu
ZyBjdXJsIHN1cHBvcnQgYW5kIGZpbGVfZ2V0IGZhaWxlZCkuIFBsZWFzZSBjb250YWN0IHN1cHBv
cnRAc3VjdXJpLm5ldCBmb3IgZ3VpZGFuY2UuXG4iOwogICAgICAgIGV4aXQoMSk7CiAgICB9Cn0K
CmVsc2UKewoKICAgICRjaCA9IGN1cmxfaW5pdCgpOwogICAgY3VybF9zZXRvcHQoJGNoLCBDVVJM
T1BUX1VSTCwgImh0dHBzOi8vJE1ZTU9OSVRPUi5zdWN1cmkubmV0L2ltb25pdG9yIik7CiAgICBj
dXJsX3NldG9wdCgkY2gsIENVUkxPUFRfUkVUVVJOVFJBTlNGRVIsIHRydWUpOwogICAgY3VybF9z
ZXRvcHQoJGNoLCBDVVJMT1BUX1BPU1QsIHRydWUpOwogICAgY3VybF9zZXRvcHQoJGNoLCBDVVJM
T1BUX1BPU1RGSUVMRFMsICJwPSRTVUNVUklQV0QmcT0iLiRfUE9TVFsnc3NjcmVkJ10pOwogICAg
Y3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1NTTF9WRVJJRllQRUVSLCB0cnVlKTsKCiAgICAkbXlf
c3VjdXJpX2VuY29kaW5nID0gY3VybF9leGVjKCRjaCk7CiAgICBjdXJsX2Nsb3NlKCRjaCk7Cgog
ICAgaWYoc3RybmNtcCgkbXlfc3VjdXJpX2VuY29kaW5nLCAiV09SS0VEOiIsNykgPT0gMCkKICAg
IHsKICAgIH0KICAgIGVsc2UKICAgIHsKICAgICAgICBpZigkbXlfc3VjdXJpX2VuY29kaW5nID09
IE5VTEwgfHwgc3RybGVuKCRteV9zdWN1cmlfZW5jb2RpbmcpIDwgMykKICAgICAgICB7CiAgICAg
ICAgICAgICRteV9zdWN1cmlfZW5jb2RpbmcgPSAieDIzNTEiOwogICAgICAgIH0KICAgICAgICBl
Y2hvICJFUlJPUjogVW5hYmxlIHRvIGNvbm5lY3QgYmFjayB0byBTdWN1cmkgKGVycm9yOiAkbXlf
c3VjdXJpX2VuY29kaW5nKS4gUGxlYXNlIGNvbnRhY3Qgc3VwcG9ydEBzdWN1cmkubmV0IGZvciBn
dWlkYW5jZS5cbiI7CiAgICAgICAgZXhpdCgxKTsKICAgIH0KfQoKCiRteV9zdWN1cmlfZW5jb2Rp
bmcgPSAgYmFzZTY0X2RlY29kZSgKICAgICAgICAgICAgICAgICAgICAgICBzdWJzdHIoJG15X3N1
Y3VyaV9lbmNvZGluZywgNykpOwoKCmV2YWwoCiAgICAkbXlfc3VjdXJpX2VuY29kaW5nCiAgICAp
Owo=

";

/* Encoded to avoid that it gets flagged by AV products or even ourselves :) */
$tempb64 =  
           base64_decode(
                          $my_sucuri_encoding);

eval(  $tempb64 
      );
exit(0); ?>    
    
