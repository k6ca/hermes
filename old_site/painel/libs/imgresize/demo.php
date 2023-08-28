
            <p><strong>Image resizer Demo.</strong></p>
            <p align="justify"><font color="#FF0000">Please remember to set <strong>&quot;write 
              permissions&quot;</strong> on directory where the resized images 
              are to be stored.</font></p>
            <p>This is the original image (test.jpg):<br />
              <img src="test.jpg" width="400" height="300" /> <br />
              First lets create the object:</p>
            <blockquote> 
              <p>require_once(&quot;hft_image.php&quot;);<br />
                $img = new hft_image(&quot;test.jpg&quot;);</p>
            </blockquote>
            <p>Lets resize it to fit into 150x150 region:</p>
            <blockquote> 
              <p>//resize the image to be no larger than 15x150, but keep X to 
                Y ratio<br />
                //so there will be no geometrical distortions:<br />
                $img-&gt;resize(150,150,&quot;-&quot;); <br />
                //save the resized image to file<br />
                $img-&gt;output_resized(&quot;test_150_minus.jpg&quot;); </p>
            </blockquote>
            <?PHP
				require_once("hft_image.php");
				//create the image from JPEG file
				$img = new hft_image("test.jpg");
                //resize the image to be no larger than 15x150, but keep X to Y ratio
                //so there will be no geometrical distortions:
                $img->resize(150,150,"-"); 
                //save the resized image to file
				//commented to save server load
//                $img->output_resized("test_150_minus.jpg");
			
			?>
            <p>Here it is: <br />
              <img src="test_150_minus.jpg" /> <br />
              <br />
              The original image is still in memory so we can resize again, with 
              other parameters and save it. Lets resize the original image in 
              such a way that it will overlap the region of 150x150. In other 
              words it will be at least 150x150 and X to Y ratio will remain:</p>
            <blockquote> 
              <p> //resize the image to be at least 15x150 big, but keep X to 
                Y ratio<br />
                //so there will be no geometrical distortions:<br />
                $img-&gt;resize(150,150,&quot;+&quot;); <br />
                //save the resized image to file<br />
                $img-&gt;output_resized(&quot;test_150_plus.jpg&quot;); </p>
            </blockquote>
            <?PHP
                //resize the image to be at least 15x150 big, but keep X to Y  ratio
                //so there will be no geometrical distortions:
                $img->resize(150,150,"+"); 
                //save the resized image to file
				//commented to save server load
  //               $img->output_resized("test_150_plus.jpg");
			
			?>
            <p>Here it is:<br />
              <img src="test_150_plus.jpg" /> </p>
            <p>Now lets resize the image to be exactly of given dimensions, most 
              likely some geometrical distortions will occur. For example if original 
              image is 400x300 and it shows a circle, after you resize it to 100 
              x 100 it will show an ellipse.</p>
            <blockquote> 
              <p> //resize the image to be exactly 15x150 big, <br />
                //most likely there will be geometrical distortions:<br />
                $img-&gt;resize(150,150,&quot;0&quot;); <br />
                //save the resized image to file<br />
                $img-&gt;output_resized(&quot;test_150_0.jpg&quot;); </p>
            </blockquote>
            <?PHP
				//resize the image to be exactly 15x150 big, <br>
                //mostlikelly there will be geometrical distortions:<br>
                $img->resize(150,150,"0"); 
                //save the resized image to file
   				//commented to save server load
//              $img->output_resized("test_150_0.jpg");
			
			?>
            <p>Here it is:<br />
              <img src="test_150_0.jpg" /> <br />
              <br />
              [&nbsp;<a href="v5/article/index.html" target="_blank">resize techniques 
              manual </a>&nbsp;][&nbsp;<a href="imgresize.zip">download</a>&nbsp;][&nbsp;<a href="http://smiledsoft.com/forum/index.php?showforum=4" target="_blank">support&nbsp;forum</a>&nbsp;] 
            </p>
            <table width="100%" border="0" align="center" cellpadding="8" cellspacing="1" bgcolor="#999999">
              <tr bgcolor="#FFFFFF"> 
                <td colspan="3"><strong>Let me present some images created with 
                  my new script [&nbsp;<a href="http://smiledsoft.com/demos/imageproc/" target="_top"><font color="#FF9900">Image 
                  Processor</font></a>&nbsp;]</strong></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td width="33%" bgcolor="#FFFFFF"> <div align="left"><strong>1. 
                    </strong>Make a thumbnail:</div></td>
                <td width="214" bgcolor="#FFFFFF"> <div align="left"><strong>2.</strong> 
                    Sharpen it for better look </div></td>
                <td width="33%" bgcolor="#FFFFFF"> <div align="left"><strong>3. 
                    </strong>Drop shadow</div></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td width="33%" bgcolor="#FFFFFF"> <div align="center"><img src="image_proc_150_150.jpg" width="150" height="112" /></div></td>
                <td><div align="center"><img src="image_proc_150_150_unsharped.jpg" width="150" height="112" /></div></td>
                <td width="33%" bgcolor="#FFFFFF"> <div align="center"><img src="image_proc_150_150_uns_shadow.jpg" width="155" height="117" /></div></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td width="33%" bgcolor="#FFFFFF"><strong>4.</strong> Watermark</td>
                <td bgcolor="#FFFFFF"><strong>5.</strong> Grayscalled watermark</td>
                <td width="33%" bgcolor="#FFFFFF"><strong>6.</strong> Rotate it!</td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td width="33%" bgcolor="#FFFFFF"> <div align="center"><img src="image_proc_150_150_uns_watermark.jpg" width="155" height="117" /></div></td>
                <td><div align="center"><img src="image_proc_150_150_uns_watermark_gray.jpg" width="155" height="117" /></div></td>
                <td width="33%" bgcolor="#FFFFFF"> <div align="center"><img src="image_proc_150_150_uns_rotate.jpg" width="165" height="131" /></div></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td width="33%" bgcolor="#FFFFFF"><strong>7.</strong> Text</td>
                <td><strong>8.</strong> 3-D Text</td>
                <td width="33%" bgcolor="#FFFFFF"><strong>9.</strong> TTF Text</td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td width="33%" bgcolor="#FFFFFF"> <div align="center"><img src="image_proc_150_150_text_s21.jpg" width="150" height="112" /></div></td>
                <td><div align="center"><img src="image_proc_150_150_text_s2.jpg" width="150" height="112" /></div></td>
                <td width="33%" bgcolor="#FFFFFF"> <div align="center"><img src="image_proc_150_150_text_a.jpg" width="150" height="112" /></div></td>
              </tr>
            </table>
</BODY>
</HTML>
