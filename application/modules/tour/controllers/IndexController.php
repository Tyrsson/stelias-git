<?php

/**
 * IndexController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';

class Tour_IndexController extends Dxcore_Controller_Action
{

	public function init() 
	{
	    parent::init();
		$this->_helper->layout()->disableLayout();
		
		$ajax = $this->_helper->getHelper('AjaxContext');
		//$ajax->setAutoJsonSerialization(false);
		$ajax->addActionContext('history', 'html')
		      ->addActionContext('main', 'html')
		      ->addActionContext('getmap', 'html')
		      ->addActionContext('getimage', 'html')
		      ->initContext();
	}

    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated IndexController::indexAction() default action
    }
    public function getimageAction()
    {
       $this->view->size = $this->_request->size;
       $this->view->size = 'large';
       
       $text[1] = 'ST. MARON St. Maron is the Father of the Maronite Church. Hermit,
                    Monk of Syria, he died in 410 A.D. The tent symbolizes the
                    hermetic, yet open air, lifestyle of prayer and penance,
                    which St. Maron founded. The Monastery of St. Maron was
                    built at the place where he died.';
       
       $text[2] = 'ST. ANNE St. Anne, the Mother of Mary and patroness of mothers
                    and children, tenderly teaches Mary the scriptures.
                    God\'s will is found in the scriptures.';
       
       $text[3] = 'JESUS, THE GOOD SHEPHERD AND THE LOST SHEEP
                    The Arabic inscription on the banner
                    "Houwatha hama lou llah" translates to "This is the Lamb
                    of God" (John1:29). The butterfly is the symbol of new life.
                    The leather belt is used to bring the lost sheep home.';
       
       $text[4] = 'THE SACRED HEART OF JESUS
                    The physical heart of Jesus, encircled by the crown of thorns,
                    symbolizes His total love through suffering for all people.';
       
       $text[5] = 'St. Nimatullah Hardini
                    St. Nimatullah (1808-1858) was an OLM monk in Kfifan,
                    where he learned to bind books and teach children. He
                    was beatified in 1998 and canonized in 2004.';
       
       $text[6] = 'St. Rafka
                    St. Rafka (1832-1914), the blind mystic of Lebanon, was
                    an example of redemptive suffering in the OLM convent
                    in Jrabta. She was beatified in 1985 and canonized in 2001.';
       
       $text[7] = 'ST. JOSEPH
                    The lily, a sign of purity, and the cedar tree show Joseph to
                    be a just and upright man. The carpenter\'s tools represent
                    his workshop, where he taught and provided for Jesus.
                    "The just man shall flourish like a palm tree, like a Cedar
                     of Lebanon shall he grow" (Psalm 92, vs.13).';
       
       $text[8] = 'OUR LADY OF FATIMA
                    In 1917, Our Lady appeared to three children. Our Lady is
                    shown speaking to Francisco and Jacinta, imploring them
                    to recite the Rosary for the conversion of sinners. Lucia is
                    not shown since she lived until 2005.';
       
       $text[9] = 'ST. PETER
                    St. Peter is shown with the scrolls of his epistle and the keys
                    to his kingdom, which Jesus gave him first (Matthew 16:19).
                    The inverted cross depicts his crucifixion.';
       
       $text[10] = 'THE AGONY IN THE GARDEN
                     After the Last Supper, Jesus in his agony prays at the
                     Mount of Olives, until his sweat becomes like drops of
                     blood as he awaits his betrayer (Luke 22:44). Pictured
                     in the background are the city of Jerusalem and the
                     Apostles Peter, James and John asleep.';
       
       $text[11] = 'ARCHANGEL MICHAEL
                    Michael, whose name in Hebrew means "who is like God,"
                    is clothed in full armor with the sword of victory over the
                    dragon on whom he treads. The dragon symbolizes Satan
                    and evil (Revelation 12:7).';
       
       $text[12] = 'ST. ELIAS, PROPHET AND PATRON OF THIS CHURCH
                    St. Elias, is clothed as a prophet of Mt. Carmel with scroll,
                    fiery sword and mantle. The Mount Carmel shield at the top
                    of the window is inscribed in Latin "Zelo Zelatus sum pro
                    Domino Deo exercituum" meaning "I have been most
                    zealous for the Lord, the God of Hosts" (I Kings, 19:14).
                    The scroll held close to his heart represents his loyalty to
                    God. The sword of fire symbolizes the fire of God that came
                    upon his altar in response to prayer and fiery chariot. The
                    sword is the symbol of zeal that he fought for the lord.
                    The cloak and belt are signs of a prophet.';
       
       $text[13] = 'THE ANNUNCIATION
                    Mary, lily of purity, studies the scriptures. The Angel
                    Gabriel announces: "Hail, full of grace, the Lord is with
                    you" (Luke 1:28). The Virgin Mother responds: "Let it be
                    done to me according to your word" (Luke 1:38).';
       
       $text[14] = 'THE CRUCIFIXION
                    Mary mourns at the side of the crucified Savior, "I am the
                    sorrowful Mother" (Great Friday Litany). "This is the King of the
                    Jews" (Luke 23:38). "It was now about midday" (Luke 23:44), but
                    the sky has deep rich colors of darkness. The skull and crossbones
                    symbolize the name of \'Golgotha\' and the death of the sin of
                    Adam. The nails symbolize the wounds by which we were saved.';
       
       $text[15] = 'ST. PAUL
                    The scroll represents his 14 epistles and the sword his
                    martyrdom. The snake and the fire refer to Paul\'s
                    shipwreck on Malta (Acts 28:3-5).';
       
       $text[16] = 'ST. THERESA OF THE CHILD JESUS
                    St. Theresa is pictured behind the cloister wall, clutching
                    the Crucifix and holding roses. "The Little Flower" -
                    Carmelite Nun of Lisiex said: "After death, I will drop
                    down from heaven a shower of roses."';
       
       $text[17] = 'ST. ANTHONY OF PADUA
                    Franciscan Friar holding the Christ Child. The burning
                    heart and treasure chest above represent his great
                    charity. He is also known as a great preacher, possessing
                    the "Lily of Purity." St. Anthony is also known for
                    restoring lost things to those asking his intercession.';
       
       $text[18] = 'THE IMMACULATE HEART OF MARY
                    Mary\'s heart, a symbol of great love, is encircled by
                    roses, while being pierced with the sword of sorrows
                    (Luke 2, 35). The heart encircled by roses symbolizes
                    her queenship and love for her spiritual children.';
       
       $text[19] = 'ST. PHILIP, THE APOSTLE
                    Following Jesus, before the multiplication of loaves,
                    Philip was asked by the Master: "Where shall we buy
                    bread for these people to eat?" (John 6:5). The loaf
                    symbolizes Philip\'s witness to the miracle
                    of Jesus feeding 5000 people.';
       
       $text[20] = 'MARY, QUEEN OF THE UNIVERSE
                    Mary stands on the earth trampling the serpent in the
                    image of Revelation, but is pictured in the clouds of heaven
                    here as our Queen - "Ave Maria" (Revelation 12:1ff).
                    Our mother was crowned as Queen in the Maronite Church.';
       
       $text[21] = 'ST. JOHN MARON
                    First Patriarch, 687 A.D., wears Patriarchal attire. The Syriac
                    Book "of the Faith" symbolizes his teaching authority. This
                    window and the St. Maron window, both facing the front of
                    the church are patrons of the Maronite Church.';
       
       $this->view->text = $text;
       $this->view->id = $this->_request->id;
    }
    public function historyAction() {}
    public function mainAction() {}
    public function getmapAction() {}
}
