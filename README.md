# summit-last-copy-checker-by-barcode


<p class="lead">Say you have a cart of books to weed, and want to check "last copy" status for each. By scanning each barcode, you'll see a message on whether it's the last copy. If it isn't the barcode will be added to a text file, which you can download when you're finished (for later loading into Alma).</p>
<p>The steps:</p>
<ul>
  <li>Select your institution.</li>
  <li>Click "Begin a new text file of barcodes".</li>
  <li>Scan each barcode in turn, taking care to view the response (you may want to set your last copies aside, etc.).</li>
  <li>When finished, click "I'm done scanning, and ready to download the text file".</li>
  <li>On the resulting page, click "Download file". This will give you a text file of non-last copy barodes.</li>
</ul>

This can be deployed to a webserver running PHP, or can be run locally on a computer running PHP.

For use on a mac, download and unzip the code. Then in terminal, navigate to the unzipped directory, and run: `php -S localhost:8000`. Then visit http://localhost:8000 in a web browser to begin using.
