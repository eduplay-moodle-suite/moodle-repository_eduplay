Usage
=====

1. In an editor, open the file picker (for example *Insert link* > *Browse repositories*) and choose **EduPlay**.
2. Paste the video link, such as ``https://eduplay.rnp.br/app/video/353479``, in the search box and search.
3. Select the result, choose **Link to external file** and confirm.

The link is inserted in the content. With ``media_eduplay`` and the Multimedia plugins filter enabled, it is displayed as the official player.

The initial listing is empty by design ("No files available") and a hint above it asks for the full link. **Search by title does not work**: typing "Documentário Eduplay 20 anos" returns nothing, only a pasted link does. Links from other hosts, ``http`` links or plain text return no results. See the scope of the proof of concept on the home page.

Where it appears
----------------

EduPlay only returns **external links**, so it is listed only in pickers that accept links, such as *Insert link* > *Browse repositories* in the editor. Pickers that accept only files (for example an image or a file upload field) do not list it, which is the normal Moodle behaviour for this kind of repository.
