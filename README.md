# Media Storage Client

### Requirements:
- PHP 7.4

### Installation

Add to composer require:

`"promorepublic/media-storage-sdk": "^0.1`

Add to composer repositories:

`{"type":"git","url":"https://github.com/PromoRepublic/media-storage-sdk"}`

### Timeouts

Requests time out after `MediaStorageClient::DEFAULT_TIMEOUT` (30s) with a `DEFAULT_CONNECT_TIMEOUT` (5s) connect timeout; a timeout throws `MediaStorageStorageClientUnknownException`.
Override with Guzzle options in the third constructor argument:

`new MediaStorageClient($apiKey, $baseUrl, ['timeout' => 60]);`

### Public methods

- `uploadMedia(string): string` to upload a media file to PR google storage
- `findAndUploadImages(array): array` to upload all media files in array to PR google storage. 
It takes each string value that contains `.jpg|.jpeg|.png|.gif` and creates `media_storage_$KEY` index
at the same to the string value level of array with a link to PR google storage.
**It converts each key of array to string**