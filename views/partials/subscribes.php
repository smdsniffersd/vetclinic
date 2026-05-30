<?php

require_once __DIR__ . '/../../config.php';

$subscribes = AllFetch('select * from subscribes');
$subscribes_options = AllFetch('select * from subscribes_options');

$optionsByPriceId = [];

foreach ($subscribes_options as $option) {
    $priceID = $option['price_id'];
    if (!isset($optionsByPriceId[$priceID])) {
        $optionsByPriceId[$priceID] = [];
    }
    $optionsByPriceId[$priceID][] = $option;
}
foreach ($subscribes as &$subscribe) {
    $subscribeID = $subscribe['id'];
    $subscribe['options'] = $optionsByPriceId[$subscribeID] ?? [];
}
unset($subscribe);
?>

<section class="tree-monit">
    <h1 class="tree-monit-h1">Keep your pets happy and healthy,</h1>
    <h1 class="tree-monit-h1">Join today for expert care</h1>
    
    <div class="carousel">
        <div class="carousel-container">
            <?php foreach ($subscribes as $values): ?>
                <li class="carousel-item li-plan">
                    <div class="price-block">
                        <span class="price-block-plan-popular"><?= htmlspecialchars($values['name']) ?></span>
                        <div class="price-block-plan-price">
                            <span class="span-price"><?= htmlspecialchars($values['price']) ?></span>
                            <span class="span-month">/Month</span>
                        </div>
                        <div class="plan-description-block">
                            <span class="plan-description"><?= htmlspecialchars($values['description']) ?></span>
                            <button class="Enroll-button-popular">Enroll Now</button>
                        </div>
                    </div>

                    <ul class="funcs_plan_ul">
                        <li class="funcs_plan_ul_li">
                            <img class="small_lapka_plan_img" src="/vetclinic/public/image/1679878917_papik-pro-p-peizazh-landscape-1.jpg" alt="small_lapka_plan">
                            <span class="span-func-popular rota">Basic Care Plan</span>
                        </li>
                        <li class="funcs_plan_ul_li">
                            <img class="small_lapka_plan_img" src="/vetclinic/public/image/small_lapka_plan_black.png" alt="small_lapka_plan">
                            <span class="span-func-popular rota">Wellness Plus Plan</span>
                        </li>
                        <li class="funcs_plan_ul_li">
                            <img class="small_lapka_plan_img" src="/vetclinic/public/image/small_lapka_plan_black.png" alt="small_lapka_plan">
                            <span class="span-func-popular rota">Senior Pet Plan</span>
                        </li>
                        <li class="funcs_plan_ul_li">
                            <img class="small_lapka_plan_img" src="/vetclinic/public/image/small_lapka_plan_black.png" alt="small_lapka_plan">
                            <span class="span-func-popular rota">Puppy & Kitten Starter Plan</span>
                        </li>
                        <li class="funcs_plan_ul_li">
                            <img class="small_lapka_plan_img" src="/vetclinic/public/image/small_lapka_plan_black.png" alt="small_lapka_plan">
                            <span class="span-func-popular rota">Premium Care Plan</span>
                        </li>
                        <li class="funcs_plan_ul_li">
                            <img class="small_lapka_plan_img" src="/vetclinic/public/image/small_lapka_plan_black.png" alt="small_lapka_plan">
                            <span class="span-func-popular rota">Family Bundle Plan</span>
                        </li>
                        <li class="funcs_plan_ul_li">
                            <img class="small_lapka_plan_img" src="/vetclinic/public/image/small_lapka_plan_black.png" alt="small_lapka_plan">
                            <span class="span-func-popular rota">Custom Care Plan</span>
                        </li>
                    </ul>
                </li>
            <?php endforeach; ?>
        </div>
        <button class="carousel-prev">←</button>
        <button class="carousel-next">→</button>
    </div>
    
    <div class="plan-ul-total">
        <ul class="block-plan_ul">
            <?php foreach ($subscribes as $values): ?>
                <li class="li-plan">
                    <?php if ($values['id'] == 1): ?>
                        <div class="Most_Popular-span">Most Popular</div>
                    <?php endif; ?>
                    <div class="price-block">
                        <span class="price-block-plan"><?= htmlspecialchars($values['name']) ?></span>
                        <div class="price-block-plan-price">
                            <span class="span-price"><?= htmlspecialchars($values['price']) ?></span>
                            <span class="span-month">/Month</span>
                        </div>
                        <div class="plan-description-block">
                            <span class="plan-description"><?= htmlspecialchars($values['description']) ?></span>
                            <button class="Enroll-button">Enroll Now</button>
                        </div>
                    </div>
                    <div class="vertical-line"></div>
                    <ul class="funcs_plan_ul">
                        <?php foreach ($values['options'] as $option): ?>
                            <li class="funcs_plan_ul_li">
                                <img class="small_lapka_plan_img" src="/vetclinic/public/image/small_lapka_plan_black.png" alt="small_lapka_plan">
                                <span class="span-func"><?= htmlspecialchars($option['name']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>