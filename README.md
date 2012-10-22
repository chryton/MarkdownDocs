# Markdown Docs

Markdown Docs is a tool to help you quickly put together documentation for a project. Somewhat inspired by [ReadTheDocs](http://readthedocs.org), Markdown Docs converts your Markdown formatted documents (duh!) into HTML. 

## Considerations

All of your Docs should contain an **h1** tag. This is what is used to generate the name of pages and their URL paths. Additionally, **h2**'s are used for level 2 navigation items. There is presently no display for h3, h4, etc...

Last, if you want your documents to be in a particular order, you can append the files with a number. For example, the first file could be *00-overview.mdown*, the subsequent document could be *01-...*, and so on and so forth.

(NOTE: Any changes made to the underlying files will be automatically regenerated on the next page load)

## Directory Structure

Each set of documents are in a folder of your choice inside the *docs/* folder. The default document set should be contained in the default folder. The directory structure is described below:

	directory
		file1.mdown
		file2.mdown
		config.json
		media/

To use files in the media directory, specify your files with the {MEDIA_DIR} specifier (i.e. `![img]({MEDIA_DIR}image.png)`)

## Configuration Options

Each set of documents has a config.json file that describes the configuration of each set of documents. For example, 

	{
		"title": "MarkdownDocs",
		"password": "password"
	}

This configuration file's title setting indicates the Title of the document (both in the header and in the `<title>` attribute of the page). If *password* is specified, the set of documents will be password-protected. 

## Future Enhancements

Searching / better navigation of documents coming soon

## Acknowledgements

The core of this code depends on a Markdown & DOM parser. For those items, I used the following:

 - **PHP Markdown**: [http://michelf.com/projects/php-markdown/](http://michelf.com/projects/php-markdown/)
 - **Simple HTML DOM**: [http://sourceforge.net/projects/simplehtmldom/](http://sourceforge.net/projects/simplehtmldom/)
 - **Idiorm**: [https://github.com/j4mie/idiorm](https://github.com/j4mie/idiorm)

