# Changelog

## 0.3.0 - 2026-10-09

- Maturity raised from ALPHA to BETA.

## 0.2.0 - 2026-10-09

- Search EduPlay videos by title with paging (10 per page), showing title and thumbnail, through the API client of local_eduplay 0.3.0. Only public, active videos that do not require authentication are listed.
- Pasted video links show the real title and thumbnail; when the video does not exist or is not public nothing is returned; when EduPlay cannot be reached the link still works with a generic title.
- Texts that are links other than a canonical EduPlay video link are never sent to EduPlay. Privacy API declares the search text sent to EduPlay.
- Requires local_eduplay 0.3.0 (setting "Query the EduPlay service" turns all requests off).

## 0.1.1 - 2026-10-09

- Add the plugin icon (it was shown broken in the file picker).
- Show a hint in the picker toolbar asking for the full video link; document that search by title is not available and when the repository is listed (only in pickers that accept external links).

## 0.1.0 - 2026-10-08

- Proof of concept: file picker repository that returns a pasted canonical EduPlay video link as an external link (depends on local_eduplay).
- No catalogue browsing (no officially confirmed EduPlay listing API) and no media copy.
- CI (moodle-plugin-ci, Moodle 4.5 and 5.3) and bilingual (en/pt_BR) documentation.
