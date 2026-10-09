Usage
=====

1. In an editor, open *Insert link* > *Browse repositories* and choose **EduPlay**.
2. In the search box, type words from the video title (for example ``Documentário Eduplay 20 anos``) and search. The results show the title and thumbnail, 10 per page, with page navigation.
3. Select the video, choose **Link to the external file** and confirm.

The link is inserted in the content. With ``media_eduplay`` and the Multimedia plugins filter enabled, it is displayed as the official player.

You can also paste the full video link (``https://eduplay.rnp.br/app/video/353479``) in the search box: the video is shown with its real title and thumbnail. If the video does not exist or is not public, nothing is returned.

What is listed
--------------

Only **public, active videos that do not require authentication**. Channels, private videos and videos that need a login do not appear. Thumbnails hosted outside ``eduplay.rnp.br`` are replaced by a generic video icon.

If EduPlay cannot be reached, a message asks to try again later or to paste the video link, and a pasted link still works (with a generic title).

Where it appears
----------------

EduPlay only returns **external links**, so it is listed only in pickers that accept links, such as *Insert link* > *Browse repositories* in the editor. Pickers that accept only files (for example an image or a file upload field) do not list it, which is the normal Moodle behaviour for this kind of repository.
