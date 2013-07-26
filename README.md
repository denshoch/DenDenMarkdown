Den-Den Markdown
=============

a.k.a でんでんマークダウン / 電電 Markdown

by Densho Channel  
<http://densho.hatenablog.com/>

*Densho-chan the spirit of ebook will help your creation!*

![](https://lh4.googleusercontent.com/-m3cvu_gKtW8/TrauQGoZbHI/AAAAAAAAJdc/ytImJ4o4DcU/s288/sd-07.png)  
Illustration (c) JCN Inc. & Garakuta.

based on:

PHP Markdown Lib by Michel Fortin  
<http://michelf.ca/>

Markdown by John Gruber  
<http://daringfireball.net/>

Features
--------

This library is implementing some features that make writing EPUB content documents easier.

* GFM style line break
* Block titles(bridgehead)
* Global Language Support
    - Japanese Ruby Annotation
    - Tate-Chu-Yoko
* Twitter account autolink syntax
* Footnotes with epub:type attribute
* EPUB pagebreak syntax
* Chunk file syntax

Requirement
-----------

This library package requires PHP 5.3 or later.

Before PHP 5.3.7, pcre.backtrack_limit defaults to 100 000, which is too small
in many situations. You might need to set it to higher values. Later PHP 
releases defaults to 1 000 000, which is usually fine.

This library also requires [Composer][composer] to use dependent library PHP Markdown Extra. Before using this library, install Composer and execute `composer install`.

[composer]: http://getcomposer.org/

Syntax
------

Description of EPUB Markdown syntax is available on <http://conv.denshochan.com/markdown>. Sorry, currently Japanese Only. 

Thanks
------

- Thanks to Makoto Kitaichi for supporting.

Copyright and License
---------------------

Den-Den Markdown  
Copyright (c) 2013 Densho Channel  
<http://densho.hatenablog.com/>  
All rights reserved.

based on:

PHP Markdown Lib
Copyright (c) 2004-2013 Michel Fortin  
<http://michelf.ca/>  
All rights reserved.

Markdown  
Copyright (c) 2003-2005 John Gruber   
<http://daringfireball.net/>   
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

*   Redistributions of source code must retain the above copyright 
    notice, this list of conditions and the following disclaimer.

*   Redistributions in binary form must reproduce the above copyright
    notice, this list of conditions and the following disclaimer in the
    documentation and/or other materials provided with the 
    distribution.

*   Neither the name "Markdown" nor the names of its contributors may
    be used to endorse or promote products derived from this software
    without specific prior written permission.

This software is provided by the copyright holders and contributors "as
is" and any express or implied warranties, including, but not limited
to, the implied warranties of merchantability and fitness for a
particular purpose are disclaimed. In no event shall the copyright owner
or contributors be liable for any direct, indirect, incidental, special,
exemplary, or consequential damages (including, but not limited to,
procurement of substitute goods or services; loss of use, data, or
profits; or business interruption) however caused and on any theory of
liability, whether in contract, strict liability, or tort (including
negligence or otherwise) arising in any way out of the use of this
software, even if advised of the possibility of such damage.

<!--                 
                                                                                
                                                                                
                                      ''          :;||;:                        
                           '::;;||1111111111|; :|1111111|'                      
                        ' '|11111111111111111111111111111|'                     
                     ';|;;;1111111111111111111111111111111;:                    
                 :||;|11111;1111111111111111111111111111111;'                   
              :;111;1111111111111111111111111111111111111111 '                  
            ;11111|111111111111111111111111111|111111111111|  '                 
          '|11111111111111111111111111111111111111111112111|   '                
         ';1111111111111111111111111111111111111111111221:11;                   
        ';1111111111111111111111122211112211111111111;:|2::11:                  
        :|1111111111111111221112|1221111222111222111    ;2:111;;;;              
       ::1111111|11111111111112;'11111121|11111|1| 11::;|122111111|             
       ;;1111111|111211122;122|::;1;21111:::;11112222222211221111111            
      ::1111111|1111211122'|21':::1'12112:   :|1222222222222211111111:          
      ::1111112|1111221211':2;'''':' |212;  ''   ;1222222$$22111111112:         
      ::1111111;11122212;;  |::'      ;121  1  :1$0$122222122 |21111122         
      :|111111:'11122222:      ':       ;1:;:'100000$;11121 ;  '12111121        
      '|111111  :1122221: ':;;:'';:        ':1108&8$2|'12111;    :111122        
       |11111:   ;2222211110000021;         ':;|$0$1|; ;22221|    '111221       
       ;11112     2222211:;008&8$1:            ;;;1;|' '$21221;     11222       
       ;11111    '221121:::;;88011'            '::;:'  |1:11211     ;2212:      
        21111    :221121:  '1111||   '::         ''':;1;   11;|      2112'      
        11111    :1|12202   ;:::;;   :'::      '  ';1$;     2 ;      1111       
        ;1111    ';'222001   ::''   ':'':::1'  ':;1$0;      ' :      212'       
         1111     : 1111;$$1:::;;::':'''||;|::;11;1|;'               11;        
         1111       |11 ' |::;:' ::::' ;|111|121112$;'               2;         
         :222       '11   '':;  '    :'|1111221211$$2    :          ;;          
          121|       ;2  '' :;'     ':  ':;222111$$$$1 '::          '           
           121        |;  : |1'      : :|1112221|2$$$21:  '                     
            ;21           '';||:  : ';122212122$22$$$22'   '                    
             ;2;            :1|||1112$222$22$2$$$$$$$$$;  ' ''                  
              :1:                2$$$$$$$$$$$$$$$$$$$$$1 '     '                
                ;;               ;$$$$$$$$$$$$$$$$$$$$$2   '      ':            
                                 1$$$$$$$$$$$$$$$$$$$$$2;     ' :               
                                |2$$$$$$$$$$$000$$$$$$$21       '               
                             ;1$00$$$$$$$$$00000000000$$0:                      
                          ;1$000000000$$$$0000000000000002                      
                       :1$0000000000000000000000000000000$;                     
                       '1$000000000000000000000000000000001                     
                         :200000000000000000000000000000002                     
                           '|112$000000000000000000000000$|                     
                                ::12000000000000000$21|;|'                      
                                 '  :|112221111|;:'  '                          
                                  ::::       :     '|;'                         
                                 : ;:        :     ': '                         
                                    '        '     '                            
                                     '       '    '                             
                                '11         '    '                              
                               :2$$:      :     '                               
                               12$$1      '                                     
                               :$2;'      '    '                                
                                         '    '                                 
                                         ;; :;;                                 
                                         1$1$$;                                 
                                        '$$$$1                                  
                                         ;;:'                                   
                                                                                
                            Verus Nullus, Omnis Licitus
-->
