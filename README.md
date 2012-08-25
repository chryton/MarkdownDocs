# Markdown Docs

Markdown Docs is a tool to help you quickly put together documentation for a project. Somewhat inspired by [ReadTheDocs](http://readthedocs.org), Markdown Docs converts your Markdown formatted documents (duh!) into HTML. You can view a quick example here: http://scottdover.com/mdd


## Considerations

All of your Docs should contain an **h1** tag. This is what is used to generate the name of pages and their URL paths. Additionally, **h2**'s are used for level 2 navigation items. There is presently no display for h3, h4, etc...

Last, if you want your documents to be in a particular order, you can append the files with a number. For example, the first file could be *00-overview.mdown*, the subsequent document could be *01-...*, and so on and so forth.

To re-index pages, simply hit the URL */rebuild-pages*

## Configuration Options

There are a few options in config.php that you can make use of

1. **DOCUMENT_TITLE**: This specifies the title in the upper left corner of the page.
2. **PASSWORD**: This is the password used to view the document pending you have the next variable enabled.
3. **USE&#95;FOR&#95;PASSWORD_ACCESS**: If set to true, it password protects the document with password above.

## Future Enhancements

There are some additions in the pipeline, coming when I have a free moment

 - Search (this should be fairly obvious)
 - Some small style tweaks
 - Toying with the idea of user's, multiple documents (projects), etc... For now, simplicity is key. 

## Acknowledgements

The core of this code depends on a Markdown & DOM parser. For those items, I used the following:

 - **PHP Markdown**: [http://michelf.com/projects/php-markdown/](http://michelf.com/projects/php-markdown/)
 - **Simple HTML DOM**: [http://sourceforge.net/projects/simplehtmldom/](http://sourceforge.net/projects/simplehtmldom/)

# Markdown Specifics

## Considerations

All of your Docs should contain an **h1** tag. This is what is used to generate the name of pages and their URL paths. Additionally, **h2**'s are used for level 2 navigation items. There is presently no display for h3, h4, etc...

Last, if you want your documents to be in a particular order, you can append the files with a number. For example, the first file could be *00-overview.mdown*, the subsequent document could be *01-...*, and so on and so forth.

To re-index pages, simply hit the URL */rebuild-pages*

## Using Images

You can use images by either providing a direct link to an image or relative linking to the media directory. To use an image in the media directory, simply drop in your image and create a link with the initial path being `{MEDIA_DIR}`