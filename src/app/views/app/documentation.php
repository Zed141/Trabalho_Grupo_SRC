<?php

/** @var yii\web\View $this */

$this->title = 'About CipheredLock';
?>
<div class="card card-lg">
    <div class="card-body">
        <div class="space-y-4">
            <div>
                <h2 class="mb-3">CipheredLock</h2>
                <div id="faq-1" class="accordion" role="tablist" aria-multiselectable="true">
                    <div class="accordion-item">
                        <div class="accordion-header" role="tab">
                            <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#faq-1-1">
                                What it is?
                            </button>
                        </div>
                        <div id="faq-1-1" class="accordion-collapse collapse show" role="tabpanel"
                             data-bs-parent="#faq-1">
                            <div class="accordion-body pt-0">
                                <div>
                                    <p>
                                        Password manager with sharing capabilities but no master password to encrypt
                                        your content. A random secret encrypts your passwords and you private/public key
                                        protects it!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header" role="tab">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#faq-1-2">What it solves?
                            </button>
                        </div>
                        <div id="faq-1-2" class="accordion-collapse collapse" role="tabpanel" data-bs-parent="#faq-1">
                            <div class="accordion-body pt-0">
                                <div>
                                    <p>Need to share passwords with a group of users but don't want to share you
                                        master password? Or don't like to share it using e-mai? Then use
                                        CipheredLock!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header" role="tab">
                            <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#faq-1-3">What it uses?
                            </button>
                        </div>
                        <div id="faq-1-3" class="accordion-collapse collapse" role="tabpanel" data-bs-parent="#faq-1">
                            <div class="accordion-body pt-0">
                                <div>
                                    <p>Using RSA with 4096bit keys and ASES with GCM for strong encryption. Supported
                                        by:</p>
                                    <ul>
                                        <li><a href="https://phpseclib.com/">phpseclib</a></li>
                                        <li><a href="https://tabler.io/">tabler.io Template</a></li>
                                        <li><a href="https://tabler.io/icons">tabler.is Icons</a></li>
                                        <li><a href="https://www.favicon-generator.org/">Favicon Generator</a></li>
                                        <li><a href="https://www.yiiframework.com/">Yii2</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



