<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Agent;
use App\Models\Post;
use App\Models\PrizeCategory;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class WdwContentSeeder extends Seeder
{
    /**
     * Seed the WDW (Who Dares Wins) demo content.
     */
    public function run(): void
    {
        $this->seedPages();
        $this->seedPosts();
        $this->seedAgents();
        $this->seedPrizes();
        $this->seedHomepageSettings();
    }

    private function seedPages(): void
    {
        $pages = [
            [
                'title' => 'About',
                'slug' => 'about',
                'sort_order' => 1,
                'body' => <<<'HTML'
<p>Who Dares Wins is a premier one loft pigeon race operated by <strong>Gary, Annette &amp; Robert Tomlinson</strong>, established in 2012 at Chapel Farm in the heart of the Derbyshire countryside, United Kingdom.</p>

<p>What began as a passion project has grown into one of the most respected one loft races in the UK and Europe. Over the past decade, WDW has built a reputation for transparency, fair racing, and outstanding bird care.</p>

<h3>Our Facility</h3>

<p>The WDW loft is purpose-built at an elevation of <strong>260 feet above sea level</strong>, providing excellent conditions for training and racing. The facility comprises <strong>26 individual sections</strong>, allowing birds to be housed in optimal group sizes with plenty of space.</p>

<p>Each section is fitted with:</p>
<ul>
    <li>Individual drinkers and feeding stations</li>
    <li>Controlled ventilation systems</li>
    <li>Perch and nest box arrangements suited to one loft racing</li>
    <li>Electronic timing and clocking systems</li>
</ul>

<p>The loft environment is carefully managed to ensure every bird receives equal treatment and the best possible preparation for race day. Birds are exercised daily around the loft and taken on progressive road training tosses throughout the season.</p>

<h3>Health &amp; Welfare</h3>

<p>Bird welfare is at the centre of everything we do. All birds are placed on a comprehensive health programme from the day they arrive. Regular veterinary checks, vaccinations, and treatments are carried out under the supervision of an experienced avian vet. We operate a strict quarantine procedure for new arrivals.</p>

<h3>Our Sponsor</h3>

<p>WDW is proudly sponsored by <strong>Vanrobaeys</strong>, one of Europe's leading pigeon feed manufacturers. All birds at Who Dares Wins are fed on Vanrobaeys premium racing mixtures, ensuring top-quality nutrition throughout the season.</p>

<h3>Our Commitment</h3>

<p>We are committed to running a fair, transparent, and exciting one loft race. Every bird is treated equally regardless of its origin, and all results are published promptly. We welcome entries from fanciers worldwide and look forward to another great season of racing.</p>
HTML,
            ],
            [
                'title' => 'Prize Money',
                'slug' => 'prize-money',
                'sort_order' => 2,
                'body' => <<<'HTML'
<p style="text-align:center;font-size:1.25rem;font-weight:700;color:#2788CF;">&pound;125,000 Guaranteed if the loft reaches Capacity.</p>

<p>Who Dares Wins has paid out over <strong>&pound;621,000 in prize money between 2022 and 2026</strong>, making it one of the highest-paying one loft races in the United Kingdom.</p>
HTML,
            ],
            [
                'title' => 'Enter Your Birds',
                'slug' => 'enter',
                'sort_order' => 3,
                'body' => <<<'HTML'
<p>Entries for the Who Dares Wins 2026 One Loft Race are now open. We welcome birds from fanciers across the UK and internationally. Entry is unlimited if loft space allows.</p>

<h3>Entry Fee</h3>

<p>The entry fee is <strong>&pound;150 per bird</strong>, which covers housing, feeding, training, veterinary care, and race entry for the full season including all 4 hot spots and the grand final from Falaise.</p>

<p>A <strong>&pound;50 deposit is required for every 3 birds entered</strong>. This deposit is non-refundable unless in exceptional circumstances.</p>

<h3>Important Requirements</h3>

<ul>
    <li>All birds must be vaccinated against PMV with an approved vaccine (e.g. Colombavac or Nobilis) a minimum of <strong>10 days before arrival</strong> at the loft</li>
    <li>Birds should be aged between <strong>35 and 40 days old</strong> before coming into the loft. The loft manager has the right to refuse any birds he feels are not old enough or sick</li>
    <li>Only pigeons carrying an approved ring will qualify for Points/Prizes</li>
    <li>All birds must be transferred in the ownership of Annette Tomlinson, Chapel Farm, Plains Lane, Blackbrook, DE56 2DD, Loft number DY1453</li>
    <li>Pedigrees/details for all birds will be required before the final race takes place</li>
</ul>

<h3>Key Dates</h3>

<ul>
    <li><strong>1st February 2026</strong> - Loft opens for bird arrivals</li>
    <li><strong>10th April 2026</strong> - Loft closes for entries</li>
    <li>Any birds which fall sick before April 11th can be replaced for free</li>
</ul>

<h3>How to Enter</h3>

<p>To enter, you can:</p>
<ol>
    <li><strong>Submit online</strong> - Use the entry form on this page</li>
    <li><strong>Send birds directly</strong> - Contact us for delivery arrangements and our courier schedule</li>
    <li><strong>Use one of our agents</strong> - We have agents in several countries who can collect and ship birds on your behalf (see our <a href="/page/agents">Agents page</a>)</li>
    <li><strong>Drop birds off in person</strong> - You are welcome to visit Chapel Farm and deliver birds personally</li>
</ol>

<h3>Venture Birds</h3>

<p>Venture Birds are available at <strong>&pound;200 each</strong> if purchased before Hot Spot 4, or <strong>&pound;450 each</strong> if purchased for the final.</p>

<h3>Pools</h3>

<p>Pools are available for all races and will be paid out 1 in every 10:</p>
<ul>
    <li><strong>Hot Spot races:</strong> 50p, &pound;1, &pound;2, &pound;3, &pound;5, &pound;10, &pound;5 Nomination (unlimited)</li>
    <li><strong>Final race:</strong> &pound;2, &pound;3, &pound;5, &pound;10, &pound;50, &pound;100, &pound;30 Nomination (unlimited)</li>
</ul>
<p>There will be a 10% admin charge deducted from the total pools entered in all races.</p>

<h3>Payment</h3>

<p>Payment can be made by bank transfer, PayPal, or cash on delivery. Full payment must be received before birds are accepted into the loft. Please contact us for bank details.</p>

<p>For any questions about entering your birds, please <a href="/page/contact">contact us</a>.</p>
HTML,
            ],
            [
                'title' => 'Race Program',
                'slug' => 'race-program',
                'sort_order' => 4,
                'body' => <<<'HTML'
<p>The Who Dares Wins 2025 race programme consists of <strong>4 qualifying hot spot races</strong> from inland England, building progressively in distance, followed by the <strong>grand final from Falaise, France</strong> and a <strong>super final from Guernsey</strong>.</p>

<h3>2025 Race Schedule</h3>

<table>
    <thead>
        <tr>
            <th>Race</th>
            <th>Liberation Point</th>
            <th>Distance</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Hot Spot 1</td>
            <td>Warwick</td>
            <td>51 miles / 82 km</td>
            <td>Sunday 10th August</td>
        </tr>
        <tr>
            <td>Hot Spot 2</td>
            <td>Stow-on-the-Wold</td>
            <td>76 miles / 122 km</td>
            <td>Sunday 17th August</td>
        </tr>
        <tr>
            <td>Hot Spot 3</td>
            <td>Marlborough</td>
            <td>116 miles / 187 km</td>
            <td>Sunday 24th August</td>
        </tr>
        <tr>
            <td>Hot Spot 4</td>
            <td>Chale</td>
            <td>168 miles / 270 km</td>
            <td>Sunday 31st August</td>
        </tr>
    </tbody>
</table>

<h3>The Grand Final</h3>

<table>
    <thead>
        <tr>
            <th>Race</th>
            <th>Liberation Point</th>
            <th>Distance</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Grand Final</strong></td>
            <td><strong>Falaise, France</strong></td>
            <td><strong>292 miles / 470 km</strong></td>
            <td><strong>Sunday 14th September</strong></td>
        </tr>
    </tbody>
</table>

<h3>The Super Final</h3>

<table>
    <thead>
        <tr>
            <th>Race</th>
            <th>Liberation Point</th>
            <th>Distance</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Super Final</strong></td>
            <td><strong>Guernsey</strong></td>
            <td><strong>250 miles / 402 km</strong></td>
            <td><strong>Sunday 28th September</strong></td>
        </tr>
    </tbody>
</table>

<h3>Training Programme</h3>

<p>Before the race season begins, all birds undergo a comprehensive training programme:</p>

<ul>
    <li>Daily loft exercise from arrival</li>
    <li>Road training commences in June, starting from 5 miles</li>
    <li>Progressive tosses building up to 100+ miles</li>
    <li>Birds are trained in all wind directions</li>
    <li>A minimum of 15 road training tosses before the first hot spot</li>
</ul>

<h3>Transportation</h3>

<p>All birds are transported to the liberation point in a <strong>Geraldy racing trailer</strong>, purpose-built for pigeon transport with individual compartments, water systems, and climate control. The trailer ensures birds arrive at the race point in peak condition.</p>

<h3>Liberation</h3>

<p>WDW operates an <strong>individual liberation</strong> system. All birds are released simultaneously from the Geraldy trailer at the designated liberation time. Liberation times are determined on the morning of the race based on weather conditions and forecasts along the race line.</p>

<h3>Clocking &amp; Timing</h3>

<p>All birds are fitted with electronic timing rings. Results are recorded automatically as birds enter the loft through the electronic timing system. Results are published on the website as soon as they are verified.</p>
HTML,
            ],
            [
                'title' => 'Race Rules',
                'slug' => 'rules',
                'sort_order' => 5,
                'body' => <<<'HTML'
<ol>
    <li>The Race Is Called <strong>Who Dares Wins International One Loft Race</strong>.</li>
    <li><strong>The Race Is Fully Affiliated To The RPRA.</strong></li>
    <li>All Races on land will close at <strong>10.00pm</strong> on the day of liberation.</li>
    <li>The Final Race from Falaise will close at <strong>8.00pm</strong> on the 2nd day after liberation, unless it turns out to be an abnormal race with limited returns.</li>
    <li>Live <strong>Benzing electronic timing</strong> will be used on all races.</li>
    <li>Any Dispute will be settled by the committee, e.g Loft Manager, Convoyer, Admin Secretary plus Assistant Admin Secretary.</li>
    <li>Venture Birds will be available at <strong>&pound;200 each</strong> if purchased before Hot Spot 4, if purchased for the final <strong>&pound;450 each</strong>.</li>
    <li>All birds must be vaccinated against PMV with an approved vaccine e.g Colombavac or Nobilis a <strong>minimum of 10 days</strong> before arrival at the loft.</li>
    <li>The loft manager would like all birds to be aged between <strong>35 and 40 days old</strong> before coming into the loft. The loft manager has the right to refuse any birds he feels are not old enough or sick.</li>
</ol>

<div style="background: #f3f4f6; border-radius: 0.5rem; padding: 1rem 1.25rem; margin: 1.5rem 0;">
    <p style="font-weight: 700; margin: 0;"><strong>10.</strong> Entry Fee is &pound;150 Per Bird, unlimited if loft space allows.</p>
</div>

<ol start="11">
    <li>There will be a <strong>&pound;50 Deposit</strong> required for every 3 birds entered. (This is non-refundable unless in exceptional circumstances.)</li>
    <li>Any birds which fall sick before April 11th can be replaced for free.</li>
    <li>All birds entered by each syndicate must be transferred in the ownership of Annette Tomlinson, Chapel Farm, Plains Lane, Blackbrook, DE56 2DD, Loft number DY1453.</li>
    <li>Following the final race, birds will be sold at auction, monies being split after expenses <strong>50/50</strong> between Who Dares Wins and the Owners.</li>
</ol>

<div style="background: #f3f4f6; border-radius: 0.5rem; padding: 1rem 1.25rem; margin: 1.5rem 0;">
    <p style="font-weight: 700; margin: 0;"><strong>15.</strong> Who Dares Wins International One Loft Race will require pedigrees/details for all birds before the final race takes place.</p>
</div>

<ol start="16">
    <li>The auction for {{year}} will commence ASAP after the final race from Falaise.</li>
    <li>Pools are 50p, &pound;1, &pound;2, &pound;3, &pound;5, &pound;10, &pound;5 Nomination which is unlimited.</li>
    <li>Pools for the final race are &pound;2, &pound;3, &pound;5, &pound;10, &pound;50, &pound;100, and &pound;30 Nom which is Unlimited.</li>
    <li>Pools are available for all races and will be paid out <strong>1 in every 10</strong>.</li>
    <li>There will be a <strong>10% admin charge</strong> deducted from the total pools entered in all races.</li>
</ol>

<div style="background: #f3f4f6; border-radius: 0.5rem; padding: 1rem 1.25rem; margin: 1.5rem 0;">
    <p style="font-weight: 700; margin: 0;"><strong>21.</strong> Birds will be accepted from 1st of February {{year}}. The loft will close for entries on 10th April {{year}}.</p>
</div>

<ol start="22">
    <li>The loft manager will reserve the right to alter/cancel liberation sites at any time. The welfare of the birds will be his priority.</li>
    <li>To win the <strong>Ace Pigeon</strong> the bird must have been to all 4 Hot Spot Races plus the final race and be clocked in Race Time.</li>
    <li>The winner of the <strong>2 bird average</strong> is the first person who clocks 2 birds in the final.</li>
    <li>Only pigeons carrying an approved ring will qualify for Points/Prizes.</li>
    <li>No family member will be allowed to participate.</li>
</ol>
HTML,
            ],
            [
                'title' => 'Agents',
                'slug' => 'agents',
                'sort_order' => 6,
                'body' => <<<'HTML'
<p>Who Dares Wins OLR has agents worldwide to help you. Feel free to contact our agents in your country.</p>
HTML,
            ],
            [
                'title' => 'Gallery',
                'slug' => 'gallery',
                'sort_order' => 7,
                'body' => <<<'HTML'
<p>Browse photos from Who Dares Wins at Chapel Farm, Derbyshire. Our gallery features images from race days, training sessions, the loft facility, bird arrivals, and special events throughout the season.</p>

<h3>The Loft &amp; Facility</h3>

<p>Take a look inside our purpose-built 26-section racing loft, the training facilities, and the surrounding Derbyshire countryside at Chapel Farm.</p>

<p><em>Photos coming soon  - check back as we add images throughout the season.</em></p>

<h3>Race Days</h3>

<p>Highlights from liberation mornings, birds on the wing, and the excitement of arrivals back at the loft on hot spot and final race days.</p>

<p><em>Photos coming soon  - check back as we add images throughout the season.</em></p>

<h3>Bird Arrivals &amp; Training</h3>

<p>Images from the arrival of young birds at Chapel Farm, early settling-in periods, and progressive road training tosses leading up to the race season.</p>

<p><em>Photos coming soon  - check back as we add images throughout the season.</em></p>

<h3>Events &amp; Presentations</h3>

<p>Photos from our Calcutta auction evenings, prize presentations, and visits from fanciers and sponsors.</p>

<p><em>Photos coming soon  - check back as we add images throughout the season.</em></p>
HTML,
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'sort_order' => 8,
                'body' => <<<'HTML'
<p>Whether you have questions about entering your birds, need information about the race programme, or just want to say hello, we'd love to hear from you.</p>

<h3>Where to Find Us</h3>

<p><strong>Who Dares Wins One Loft Race</strong><br>
Chapel Farm<br>
Plains Lane, Blackbrook<br>
Belper, Derbyshire DE56 2DD<br>
United Kingdom</p>

<h3>Get in Touch</h3>

<p><strong>Phone:</strong> +44 7368 307667<br>
<strong>Email:</strong> who.dares.wins@aol.com</p>

<h3>Visiting Chapel Farm</h3>

<p>Visitors are welcome by prior arrangement. If you would like to visit the loft, see the facility, or deliver birds in person, please telephone or email ahead to arrange a convenient time. We are always happy to show fanciers around.</p>

<h3>The Facility</h3>

<p>Our purpose-built 260ft loft with 26 individual sections provides world-class accommodation for your birds. Located in the heart of the Derbyshire countryside, Chapel Farm offers an ideal environment for training and racing.</p>

<h3>Sending Birds</h3>

<p>If you are based overseas, please get in touch with your nearest <a href="/page/agents">agent</a> who can arrange collection and shipping. UK-based fanciers can deliver birds directly to the loft by arrangement, or we can recommend trusted transport services.</p>
HTML,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                array_merge($page, ['is_published' => true])
            );
        }
    }

    private function seedAgents(): void
    {
        $agents = [
            [
                'name' => 'Sjaak Buwalda',
                'country' => 'Holland',
                'email' => 'buwaldaj@hotmail.com',
                'phone' => '00 31 655855998',
                'photo' => 'agents/sjaak-buwalda.jpg',
                'sort_order' => 1,
            ],
            [
                'name' => 'Maria Steenberg',
                'country' => 'Denmark',
                'email' => 'maria_steenberg@hotmail.com',
                'phone' => '+4540631768',
                'photo' => 'agents/maria-steenberg.jpg',
                'sort_order' => 2,
            ],
        ];

        foreach ($agents as $data) {
            Agent::updateOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['is_active' => true])
            );
        }
    }

    private function seedPrizes(): void
    {
        $hotSpotPositions = [
            ['label' => '1st', 'amount' => 'TBC', 'sort_order' => 1],
            ['label' => '2nd', 'amount' => 'TBC', 'sort_order' => 2],
            ['label' => '3rd', 'amount' => 'TBC', 'sort_order' => 3],
            ['label' => '4th', 'amount' => 'TBC', 'sort_order' => 4],
            ['label' => '5th', 'amount' => 'TBC', 'sort_order' => 5],
            ['label' => '6th', 'amount' => 'TBC', 'sort_order' => 6],
            ['label' => '7th', 'amount' => 'TBC', 'sort_order' => 7],
            ['label' => '8th', 'amount' => 'TBC', 'sort_order' => 8],
            ['label' => '9th', 'amount' => 'TBC', 'sort_order' => 9],
        ];

        $categories = [
            [
                'name' => 'Grand Final',
                'type' => 'positions',
                'sort_order' => 1,
                'positions' => [
                    ['label' => '1st', 'amount' => 'To Be Confirmed', 'sort_order' => 1],
                    ['label' => '2nd', 'amount' => 'To Be Confirmed', 'sort_order' => 2],
                    ['label' => '3rd', 'amount' => 'To Be Confirmed', 'sort_order' => 3],
                    ['label' => '4th', 'amount' => 'To Be Confirmed', 'sort_order' => 4],
                    ['label' => '5th', 'amount' => 'To Be Confirmed', 'sort_order' => 5],
                    ['label' => '6th-10th', 'amount' => 'To Be Confirmed', 'sort_order' => 6],
                    ['label' => '11th-15th', 'amount' => 'To Be Confirmed', 'sort_order' => 7],
                    ['label' => '16th-20th', 'amount' => 'To Be Confirmed', 'sort_order' => 8],
                    ['label' => '21st-25th', 'amount' => 'To Be Confirmed', 'sort_order' => 9],
                    ['label' => '26th-30th', 'amount' => 'To Be Confirmed', 'sort_order' => 10],
                    ['label' => '30th+', 'amount' => 'To Be Confirmed', 'sort_order' => 11],
                ],
            ],
            [
                'name' => 'Hot Spot Race 1',
                'type' => 'positions',
                'sort_order' => 2,
                'positions' => $hotSpotPositions,
            ],
            [
                'name' => 'Hot Spot Race 2',
                'type' => 'positions',
                'sort_order' => 3,
                'positions' => $hotSpotPositions,
            ],
            [
                'name' => 'Hot Spot Race 3',
                'type' => 'positions',
                'sort_order' => 4,
                'positions' => $hotSpotPositions,
            ],
            [
                'name' => 'Hot Spot Race 4',
                'type' => 'positions',
                'sort_order' => 5,
                'positions' => $hotSpotPositions,
            ],
            [
                'name' => 'Ace Pigeon',
                'type' => 'award',
                'sort_order' => 6,
                'positions' => [
                    ['label' => 'Winner', 'amount' => 'TBC', 'sort_order' => 1],
                ],
            ],
            [
                'name' => 'First to Clock 2 Birds in Final',
                'type' => 'award',
                'sort_order' => 7,
                'positions' => [
                    ['label' => 'Winner', 'amount' => 'TBC', 'sort_order' => 1],
                ],
            ],
        ];

        foreach ($categories as $data) {
            $positions = $data['positions'];
            unset($data['positions']);

            $category = PrizeCategory::updateOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['is_active' => true])
            );

            $category->positions()->delete();
            foreach ($positions as $position) {
                $category->positions()->create($position);
            }
        }
    }

    private function seedHomepageSettings(): void
    {
        Setting::set('season_year', '2026');
        Setting::set('entries_enabled', '1');
        Setting::set('homepage_mode', 'pre-season');
        Setting::set('homepage_pigeon_count', '1650');
        Setting::set('homepage_team_count', '284');
        Setting::set('homepage_content', <<<'HTML'
<h2>Who Dares Wins {{year}} - Entries Now Open</h2>

<p>Welcome to the {{year}} Who Dares Wins International One Loft Race, operated by <strong>Gary, Annette and Robert Tomlinson</strong> at Chapel Farm in the heart of the Derbyshire countryside.</p>

<p>Now entering our <strong>14th season</strong>, WDW has established itself as one of the most respected and highest-paying one loft races in the UK and Europe, having paid out over <strong>&pound;621,000 in prize money between 2022 and {{year}}</strong>.</p>

<h3>The {{year}} Season</h3>

<p>This year's race programme features <strong>4 qualifying hot spot races</strong> from France, building from 230 miles up to 275 miles, followed by the <strong>grand final from Falaise at 292 miles (470 km)</strong>. All birds are transported in our purpose-built Geraldy racing trailer and released using our individual liberation system.</p>

<p>The loft is open for bird arrivals from <strong>1st February {{year}}</strong>, with entries closing on <strong>10th April {{year}}</strong>. Entry is <strong>&pound;150 per bird</strong>, covering housing, feeding, veterinary care, training, and entry into all races including the grand final.</p>

<h3>Our Facility</h3>

<p>Chapel Farm sits at <strong>260 feet above sea level</strong>, providing excellent conditions for training and racing. Our purpose-built facility comprises <strong>26 individual sections</strong>, each fitted with individual drinkers, feeding stations, controlled ventilation, and electronic timing systems. Every bird receives equal treatment and the best possible preparation for race day.</p>

<p>All birds at Who Dares Wins are fed on <strong>Vanrobaeys</strong> premium racing mixtures, ensuring top-quality nutrition throughout the season.</p>

<h3>Get Involved</h3>

<p>Whether you are a seasoned one loft enthusiast or entering for the first time, we welcome entries from fanciers across the UK and worldwide. We have agents in several countries who can assist with collecting and shipping your birds.</p>

<p><a href="/enter">Enter your birds online</a> or <a href="/page/contact">contact us</a> for more information. We look forward to another great season of racing at Who Dares Wins.</p>
HTML
        );

        // Race map
        Setting::set('race_map_loft_lat', '53.05');
        Setting::set('race_map_loft_lng', '-1.48');
        Setting::set('race_map_points', json_encode([
            ['name' => 'Warwick', 'lat' => '52.282', 'lng' => '-1.585', 'distance' => '51 Miles / 82 km', 'date' => 'Sunday 10th August', 'type' => 'hotspot'],
            ['name' => 'Stow-on-the-Wold', 'lat' => '51.932', 'lng' => '-1.723', 'distance' => '76 Miles / 122 km', 'date' => 'Sunday 17th August', 'type' => 'hotspot'],
            ['name' => 'Marlborough', 'lat' => '51.421', 'lng' => '-1.729', 'distance' => '116 Miles / 187 km', 'date' => 'Sunday 24th August', 'type' => 'hotspot'],
            ['name' => 'Chale', 'lat' => '50.593', 'lng' => '-1.293', 'distance' => '168 Miles / 270 km', 'date' => 'Sunday 31st August', 'type' => 'hotspot'],
            ['name' => 'Falaise', 'lat' => '48.895', 'lng' => '-0.196', 'distance' => '292 Miles / 470 km', 'date' => 'Sunday 14th September', 'type' => 'final'],
            ['name' => 'Guernsey', 'lat' => '49.455', 'lng' => '-2.540', 'distance' => '250 Miles / 402 km', 'date' => 'Sunday 28th September', 'type' => 'super'],
        ]));

        // Entry form settings
        Setting::set('entry_year', '2026');
        Setting::set('entry_fee', '150');
        Setting::set('entry_currency', '£');
        Setting::set('entry_max_birds', '20');
        Setting::set('entry_deadline', '2026-04-10');
        Setting::set('entry_is_open', '1');
        Setting::set('entry_notes', 'All birds must be vaccinated against PMV with an approved vaccine (e.g. Colombavac or Nobilis) a minimum of 10 days before arrival at the loft. Birds should be aged between 35 and 40 days old. A £50 deposit is required for every 3 birds entered.');
        Setting::set('entry_pdf_intro', 'Please complete this form and return it with your birds. Entry fee: £150 per bird. All birds must be PMV vaccinated a minimum of 10 days before arrival. Deadline: 10th April 2026.');
    }

    private function seedPosts(): void
    {
        $posts = [
            [
                'title' => 'Welcome to the New Who Dares Wins Website',
                'slug' => 'welcome-to-the-new-website',
                'post_type' => 'news',
                'is_pinned' => true,
                'is_published' => true,
                'published_at' => '2026-03-01 10:00:00',
                'excerpt' => 'We are delighted to launch our brand new website for the Who Dares Wins One Loft Race. Stay up to date with all the latest news, results, and live race coverage right here.',
                'body' => <<<'HTML'
<h2>Welcome to Our New Website</h2>

<p>We are delighted to launch the brand new Who Dares Wins website. This has been a long time in the making and we are proud to finally share it with you.</p>

<p>The new site has been built from the ground up to give you the best possible experience. Here is what you can expect:</p>

<ul>
    <li><strong>Live Race Results</strong>  - Follow every hot spot and the grand final as birds clock in, with results updated in real time</li>
    <li><strong>Bird Performance Tracking</strong>  - Look up your birds and see their full race history, speeds, and placings across the season</li>
    <li><strong>Livestream Coverage</strong>  - Watch live on race days as birds return to the loft</li>
    <li><strong>News &amp; Updates</strong>  - Training reports, health updates, and all the latest from Chapel Farm</li>
</ul>

<p>We want to thank all of our loyal supporters and entrants who have made WDW what it is today. With over &pound;621,000 paid out between 2022 and 2026, we are committed to making Who Dares Wins one of the top one loft races in Europe.</p>

<p>Entries are open for the new season. Head over to our <a href="/enter">Enter Your Birds</a> page for full details, or <a href="/contact">contact us</a> if you have any questions.</p>

<p>Here's to a great season ahead!</p>

<p><em> - Gary, Annette &amp; Robert Tomlinson</em></p>
HTML,
            ],
            [
                'title' => 'Training Flights Under Way for 2026 Season',
                'slug' => 'training-flights-under-way-2026',
                'post_type' => 'update',
                'is_pinned' => false,
                'is_published' => true,
                'published_at' => '2026-03-20 14:30:00',
                'excerpt' => 'Road training has started for the 2026 season. All birds are looking fit and healthy after a successful settling-in period at Chapel Farm.',
                'body' => <<<'HTML'
<h2>Training Flights Under Way</h2>

<p>We are pleased to report that road training has officially begun for the 2026 Who Dares Wins season. After a successful settling-in period and several weeks of loft exercise, the birds are now out on the road.</p>

<h3>Training Progress</h3>

<p>So far the birds have completed the following tosses:</p>

<ul>
    <li><strong>5 miles</strong>  - Two tosses, all birds home within 15 minutes. Excellent.</li>
    <li><strong>10 miles</strong>  - Two tosses in different wind directions. Birds trapping well.</li>
    <li><strong>20 miles</strong>  - First toss completed yesterday. Very pleased with the speed of return.</li>
</ul>

<p>The birds are looking fit and healthy. The Vanrobaeys feed is doing its job and we can see the condition building nicely. Losses have been minimal during settling, which is always encouraging at this stage of the season.</p>

<h3>Next Steps</h3>

<p>Over the coming weeks we will be building distances up to 50 miles before the first longer training tosses in June. We aim to have all birds trained to 100+ miles before Hot Spot 1.</p>

<p>We will continue to post regular training updates here on the website. If you have birds entered and want to check on their progress, keep an eye on the bird performance section once racing begins.</p>

<p>The season is shaping up well. Watch this space!</p>
HTML,
            ],
            [
                'title' => 'Live Race Day Coverage',
                'slug' => 'live-race-day-coverage',
                'post_type' => 'livestream',
                'livestream_url' => 'https://www.youtube.com/watch?v=VSfwCwad8U8',
                'is_pinned' => false,
                'is_published' => true,
                'published_at' => '2026-03-25 07:00:00',
                'excerpt' => 'Watch the live feed from Chapel Farm as birds return on race day. We stream live from the loft so you can follow the action wherever you are in the world.',
                'body' => <<<'HTML'
<h2>Live Race Day Coverage</h2>

<p>Follow the action live from Chapel Farm on race days. Our loft camera streams live on YouTube so you can watch birds returning from the race point in real time, wherever you are in the world.</p>

<p>The livestream typically begins around an hour before the estimated time of arrival and runs until the last birds are home. Commentary and updates will be posted alongside the stream.</p>

<h3>What to Expect</h3>

<ul>
    <li>Live video from the loft entrance showing birds arriving and trapping</li>
    <li>Real-time clocking updates as birds are timed in</li>
    <li>Commentary from the WDW team on conditions, wind, and race progress</li>
</ul>

<p>Make sure to subscribe to our YouTube channel and turn on notifications so you don't miss a race. Share the stream with your fellow fanciers and enjoy the excitement of race day from your armchair.</p>
HTML,
            ],
            [
                'title' => 'Bird Arrivals Now Open for 2026',
                'slug' => 'bird-arrivals-open-2026',
                'post_type' => 'news',
                'is_pinned' => false,
                'is_published' => true,
                'published_at' => '2026-02-15 09:00:00',
                'excerpt' => 'We are now accepting bird entries for the 2026 season. Get your entries in early to give your birds the best preparation.',
                'body' => <<<'HTML'
<h2>Bird Arrivals Now Open</h2>

<p>We are pleased to announce that Chapel Farm is now open for bird arrivals for the 2026 Who Dares Wins season.</p>

<p>As always, we encourage fanciers to get their birds to us as early as possible. Early arrivals benefit from:</p>

<ul>
    <li>A longer settling-in period at the loft</li>
    <li>More time to acclimatise to the environment and other birds</li>
    <li>Additional training tosses before the hot spot races begin</li>
    <li>Better overall race preparation</li>
</ul>

<h3>Entry Details</h3>

<p>The entry fee remains at <strong>&pound;150 per bird</strong> for the 2026 season. This covers everything from arrival through to the grand final from Falaise.</p>

<p>The deadline for bird arrivals is <strong>10th April</strong>, but we strongly recommend sending birds as early as possible. Birds are accepted from 1st February 2026.</p>

<p>To enter, visit our <a href="/enter">Enter Your Birds</a> page or contact us directly. If you are based overseas, please get in touch with your nearest <a href="/agents">agent</a> who can arrange collection and shipping.</p>

<p>We look forward to receiving your birds and to another exciting season of racing at Who Dares Wins.</p>
HTML,
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['slug' => $post['slug']],
                $post
            );
        }
    }
}
