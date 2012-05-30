# Pages #

## Overview ##

The pages section allows you to edit various non-product related items on the site. You can view a list of the pages on your site by visiting [http://centuryproductsllc.com/admin/pages](http://centuryproducts.com/admin/pages) or logging in to your admin at [http://centuryproductsllc.com/admin](http://centuryproductsllc.com/admin).

## Editing page content ##

1. Click on a *page* title to begin editing. For this example, we'll use the "About us" page.
2. Click the *blue* link on the list of pages that says "About Us"
3. This will open the page viewer. To begin editing the content, click the edit button at the top of the screen: ![About Us][one]
4. Once your click the edit screen, you can change the content as you need to
5. To add / edit images on a page, read *How To Insert / Replace an Image*

## How To Insert / Replace an Image ##

1. Begin by editing a page
2. Click the insert button on the toolbar and choose image: ![Choose Image][two]
3. This will open a new window to upload an image...or choose an image that is currently on the site.
4. When uploading an image, it will ask you what size to use. Choose the *Large* size from the pulldown
5. Looking at our *About Us* example, I've added a new image (illustrated below): ![Added new image][three]
6. After uploading the new image, click on the current image and click *remove* to delete the image
7. Last, you need to give the image a css class to tell it where to be positioned on the page. To do that, click *View HTML* on the editing toolbar
8. The pertinent line of code looks something like this `<img src="//cdn.shopify.com/s/files/1/0130/7122/files/paper-products.jpg?3271" />`
9. After the `<img` and before the `src=`, insert the following information `class="fr"`. This tells the image to float to the right on the page
10. Last, click the *Save Page* button for the changes to take effect


[one]: ../images/about-us-shot.png
[two]: ../images/choose-image.png
[three]: ../images/added-image.png