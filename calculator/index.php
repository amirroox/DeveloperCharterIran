<?php
$cache = json_decode(file_get_contents("cache.json"), true);
$dollar = $cache["price"];
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>محاسبه هزینه پروژه برنامه‌نویسی</title>
    <link rel="stylesheet" href="../assets/calculator.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h2><a href="/">صفحه اصلی</a></h2>
            <h1>محاسبه هزینه پروژه برنامه نویسی</h1>
            <p>ابزار حرفه‌ای برای تعیین قیمت دقیق</p>
        </div>

        <div class="tabs">
            <button class="tab-btn active" data-tab="basics">اطلاعات پایه <br> (Basic information)</button>
            <button class="tab-btn" data-tab="backend">بک اند <br> (Backend)</button>
            <button class="tab-btn" data-tab="frontend">فرانت اند <br> (Frontend)</button>
            <button class="tab-btn" data-tab="tools">ابزارها <br> (Tools)</button>
            <button class="tab-btn" data-tab="complexity">پیچیدگی <br> (Complexity)</button>
            <button class="tab-btn" data-tab="factors">عوامل <br> (Factors)</button>
            <button class="tab-btn" data-tab="results">نتایج <br> (Results)</button>
            <button class="tab-btn" data-tab="whyprice">چرا؟ <br> (Why?)</button>
        </div>

        <div class="content-wrapper">
            <!-- TAB 1: BASICS -->
            <div id="basics" class="tab-content active">
                <div class="section">
                    <h2>Basic information - اطلاعات پایه</h2>

                    <div class="form-group">
                        <label for="dollarPrice">قیمت 1 دلار (تومان):
                            <button id="updateBtnDoller">بروزرسانی قیمت دلار</button>
                        </label>
                        <input type="number" id="dollarPrice" value="<?= $dollar ? $dollar : 120000 ?>" min="1">
                    </div>

                    <div class="form-group">
                        <label>سطح تخصص: (ت/س: تومان در هر ساعت - د/س: دلار در ساعت) - قیمت به میانگین دو رنج محسابه میشود</label>
                        <div class="skill-level">
                            <div>
                                <button class="skill-btn" data-level="junior" data-min="150000" data-max="300000">
                                    (Junior) جونیور<br><small>150-300 ت/س</small>
                                </button>
                                <button class="skill-btn" data-level="middle" data-min="300000" data-max="600000">
                                    (Mid-level) میدلول<br><small>300-600 ت/س</small>
                                </button>
                                <button class="skill-btn" data-level="senior" data-min="600000" data-max="1200000">
                                    (Senior) سنیور<br><small>600-1200 ت/س</small>
                                </button>
                                <button class="skill-btn" data-level="architect" data-min="1200000" data-max="3000000">
                                    (Architect) معمار<br><small>1200-3000 ت/س</small>
                                </button>
                                <button class="skill-btn" data-level="expert" data-min="3000000" data-max="5000000">
                                    (Expert) متخصص<br><small>3000-5000 ت/س</small>
                                </button>
                            </div>

                            <div>
                                <button class="skill-btn" data-level="junior" data-min="<?= $dollar * 15  ?>" data-max="<?= $dollar * 35  ?>">
                                    (Junior) جونیور جهانی به دلار<br><small>15-35 د/س</small>
                                </button>
                                <button class="skill-btn" data-level="middle" data-min="<?= $dollar * 35  ?>" data-max="<?= $dollar * 70  ?>">
                                    (Mid-level) میدلول جهانی به دلار<br><small>35-70 د/س</small>
                                </button>
                                <button class="skill-btn" data-level="senior" data-min="<?= $dollar * 70  ?>" data-max="<?= $dollar * 130  ?>">
                                    (Senior) سنیور جهانی به دلار<br><small>70-130 د/س</small>
                                </button>
                                <button class="skill-btn" data-level="architect" data-min="<?= $dollar * 130  ?>" data-max="<?= $dollar * 190  ?>">
                                    (Architect) معمار جهانی به دلار<br><small>130-190 د/س</small>
                                </button>
                                <button class="skill-btn" data-level="expert" data-min="<?= $dollar * 190  ?>" data-max="<?= $dollar * 250  ?>">
                                    (Expert) متخصص جهانی به دلار<br><small>190-250 د/س</small>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="hours">تعداد ساعات (مفید):</label>
                        <input type="number" id="hours" value="40" min="0.5" step="0.5">
                    </div>

                    <div class="form-group">
                        <label for="hourlyRate">نرخ ساعتی دستی به تومان (اختیاری) - برای مثال 500000:</label>
                        <input type="number" id="hourlyRate" value="0" min="0">
                    </div>

                    <div class="form-group">
                        <label for="teamSize">تعداد اعضای تیم:</label>
                        <input type="number" id="teamSize" value="1" min="1" max="20">
                    </div>
                </div>
            </div>

            <!-- TAB 2: BACKEND -->
            <div id="backend" class="tab-content">
                <div class="section">
                    <h2>Backend - زبان ها و فریم ورک ها</h2>

                    <h3 class="category-title">PHP - پی اچ پی</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="0.95"> PHP Vanilla</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.00"> Laravel</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.05"> Symfony</label>
                        <label><input type="checkbox" class="tech" data-multiplier="0.85"> Yii2</label>
                        <label><input type="checkbox" class="tech" data-multiplier="0.95"> CodeIgniter</label>
                        <label><input type="checkbox" class="tech" data-multiplier="0.90"> Slim Framework</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.05"> Laminas</label>
                        <label><input type="checkbox" class="tech" data-multiplier="0.90"> WordPress (Plugin/Theme Dev)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="0.70"> WordPress (Template)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="0.65"> Drupal</label>
                        <label><input type="checkbox" class="tech" data-multiplier="0.75"> Joomla</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.10"> Magento</label>
                    </div>

                    <h3 class="category-title">Python - پایتون</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.00"> Django</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.10"> Django REST Framework</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.05"> Flask</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.10"> FastAPI</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Tornado</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.20"> aiohttp</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Celery (Async jobs)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.30"> Scrapy (Crawling)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> asyncio</label>
                    </div>

                    <h3 class="category-title">Node.js / JavaScript - جاوا اسکریپت و نود جی اس</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.05"> Express.js</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.10"> NestJS</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Fastify</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.20"> Koa</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Hapi</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.30"> Sails.js</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> AdonisJS</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.30"> TypeScript Backend</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.40"> Deno</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.45"> Bun Runtime</label>
                    </div>

                    <h3 class="category-title">Java - جاوا</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.20"> Spring Boot</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Spring Cloud Microservices</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Quarkus</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.10"> Micronaut</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.05"> Jakarta EE</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.00"> Vanilla Java</label>
                    </div>

                    <h3 class="category-title">Kotlin - کاتلین</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Ktor</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Spring Boot (Kotlin)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Kotlin Coroutines</label>
                    </div>

                    <h3 class="category-title">C# / .NET - سی شارپ و دات نت</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.20"> ASP.NET Core</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> .NET 6+ API</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.10"> Entity Framework Core</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Blazor (Server/WebAssembly)</label>
                    </div>

                    <h3 class="category-title">Rust - راست</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Actix Web</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Rocket</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Axum</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Warp</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Tokio Async</label>
                    </div>

                    <h3 class="category-title">Go - گولنگ</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Gin</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Fiber</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Echo</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Go Kit</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Go Micro</label>
                    </div>

                    <h3 class="category-title">++C - سی پلاس پلاس</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> cpprestsdk</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Boost Beast</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.4"> Pistache</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.45"> Crow</label>
                    </div>

                    <h3 class="category-title">C - سی</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.4"> libhv</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> civetweb</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.45"> Kore.io</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.5"> Custom Native Server</label>
                    </div>

                    <h3 class="category-title">Scala - اسکالا</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Play Framework</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Akka HTTP</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> http4s</label>
                    </div>

                    <h3 class="category-title">Elixir - اکسیر</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Phoenix Framework</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> LiveView</label>
                    </div>

                    <h3 class="category-title">Ruby - روبی</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Ruby on Rails</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Sinatra</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Hanami</label>
                    </div>

                    <h3 class="category-title">Haskell - هسکل</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Yesod</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Scotty</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.4"> Servant</label>
                    </div>

                    <h3 class="category-title">Assembly - اسمبلی</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="2.0"> Low-level Optimization</label>
                        <label><input type="checkbox" class="tech" data-multiplier="2.2"> Full Native Implementation</label>
                        <label><input type="checkbox" class="tech" data-multiplier="2.5"> System-level Programming</label>
                    </div>

                    <h3 class="category-title">Architectures - معماری ها</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="complexity" data-multiplier="1.20"> Monolithic Clean Architecture</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> Microservices</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.30"> Event-driven Architecture</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.40"> CQRS / Event Sourcing</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.25"> Domain-driven Design (DDD)</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.15"> Modular Monolith</label>
                    </div>
                </div>
            </div>

            <!-- TAB 3: FRONTEND -->
            <div id="frontend" class="tab-content">
                <div class="section">
                    <h2>Frontend - فریم ورک ها و ابزار ها</h2>

                    <h3 class="category-title">JavaScript / TypeScript - جاوا اسکریپت و تایپ اسکریپت</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> JavaScript (Vanilla)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> TypeScript</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.05"> Web Components</label>
                    </div>

                    <h3 class="category-title">React Ecosystem - ری اکت</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> React</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Next.js</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Remix</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Gatsby</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Preact</label>
                    </div>

                    <h3 class="category-title">Vue Ecosystem - ویو</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.0"> Vue.js</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Nuxt.js</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.05"> VitePress</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Quasar</label>
                    </div>

                    <h3 class="category-title">Modern Frameworks - فریمورک های مدرن</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Angular</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> SvelteKit</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> SolidJS</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Qwik</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Alpine.js</label>
                    </div>

                    <h3 class="category-title">CSS & Styling - سی اس اس و استایل دهی</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.0"> Tailwind CSS</label>
                        <label><input type="checkbox" class="tech" data-multiplier="0.95"> Bootstrap</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.05"> Material UI / MUI</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Chakra UI</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Ant Design</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.0"> Styled Components</label>
                        <label><input type="checkbox" class="tech" data-multiplier="0.9"> SASS/SCSS</label>
                    </div>

                    <h3 class="category-title">State Management - کنترل وضعیت</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Redux Toolkit</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.05"> Zustand</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> MobX</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Recoil</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> GraphQL / Apollo</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Vuex / Pinia</label>
                    </div>

                    <h3 class="category-title">Build Tools - ابزار ساخت و بیلد کردن</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Webpack</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.05"> Vite</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Rollup</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> ESBuild</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Turbopack</label>
                    </div>

                    <h3 class="category-title">Testing - تست و تست نویسی</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Jest</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Vitest</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Cypress</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Playwright</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Testing Library</label>
                    </div>

                    <h3 class="category-title">Mobile Development - توسعه اندروید</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> React Native</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Flutter</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.4"> Swift (iOS)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Kotlin (Android)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Ionic</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Capacitor</label>
                    </div>

                    <h3 class="category-title">3D & WebGL -سه بعدی سازی و وب جی ال</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.4"> Three.js</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.45"> Babylon.js</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> WebGPU</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.5"> Unity WebGL</label>
                    </div>

                    <h3 class="category-title">Performance / SSR / PWA - بهینه سازی، سرور سایت رندرینگ، وب اپلیکشن</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> PWA</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Server Side Rendering</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Web Workers</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> WebAssembly Integration</label>
                    </div>

                    <h3 class="category-title">Micro Frontend Architecture - معماری میکرو فرانت اند</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Module Federation</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Single-SPA</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Web Components (MF)</label>
                    </div>

                    <h3 class="category-title">WebAssembly - وب اسمبلی</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.4"> WASM (Rust)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> WASM (C/C++)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.45"> WebAssembly + JS Glue</label>
                    </div>

                </div>
            </div>

            <!-- TAB 4: TOOLS & SERVICES -->
            <div id="tools" class="tab-content">
                <div class="section">
                    <h2>Tools - ابزار ها و سرویس ها</h2>

                    <!-- DATABASES -->
                    <h3 class="category-title">Databases - دیتابیس ها</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="0.95"> MySQL / MariaDB</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.0"> PostgreSQL</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> MongoDB</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Redis</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Elasticsearch</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> DynamoDB</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Cassandra</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> ClickHouse</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Neo4j (Graph DB)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Firebase Firestore</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Supabase</label>
                    </div>

                    <!-- CLOUD & DEVOPS -->
                    <h3 class="category-title">Cloud & DevOps - کلود و دوآپس</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Docker</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Kubernetes</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> AWS</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Google Cloud</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Microsoft Azure</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> DigitalOcean</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Cloudflare</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.4"> CI/CD (GitHub Actions, GitLab, Jenkins)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Terraform</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Ansible</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> Nginx</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Apache</label>
                    </div>

                    <!-- APIs & Integrations -->
                    <h3 class="category-title">APIs & Integrations - اِی پی آی و یکپارچه سازی ها </h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> REST API</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> GraphQL</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> gRPC</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Payment Gateway</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> SMS / Email Service</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> OAuth2 / JWT Security</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Third-Party API Integration</label>
                    </div>

                    <!-- Messaging & Real-time -->
                    <h3 class="category-title">Messaging & Real-Time - پیام رسانی و ریئل تایمینگ</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> RabbitMQ</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Kafka</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> WebSocket</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Socket.io</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> MQTT</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> SSE (Server Sent Events)</label>
                    </div>

                    <!-- Search & Analytics -->
                    <h3 class="category-title">Search & Analytics - جستجو و تحلیل</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Elasticsearch</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Solr</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.1"> Google Analytics</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Matomo Analytics</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> BigQuery</label>
                    </div>

                    <!-- AI / ML -->
                    <h3 class="category-title">AI / ML - هوش مصنوعی و ماشین لرنینگ</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.6"> TensorFlow</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.55"> PyTorch</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.7"> NLP (Transformers, BERT, LLMs)</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.65"> Computer Vision</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.55"> Scikit-Learn</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.75"> Multi-Model AI Integration</label>
                    </div>

                    <!-- Monitoring & Logging -->
                    <h3 class="category-title">Monitoring & Logging - مانیتورینگ</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> Prometheus</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Grafana</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.15"> Sentry</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> Loki</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> ELK Stack</label>
                    </div>

                    <!-- Security -->
                    <h3 class="category-title">Security - امنیت</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="tech" data-multiplier="1.25"> WAF / Firewall Setup</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.35"> Penetration Testing</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.3"> OWASP Security Hardening</label>
                        <label><input type="checkbox" class="tech" data-multiplier="1.2"> SSL / HTTPS Configuration</label>
                    </div>
                </div>

            </div>

            <!-- TAB 5: COMPLEXITY -->
            <div id="complexity" class="tab-content">
                <div class="section">
                    <h2>Complexity - پیچیدگی و معماری</h2>

                    <!-- SYSTEM ARCHITECTURE -->
                    <h3 class="category-title">System Architecture - معماری سیستم</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="complexity" data-multiplier="1.1"> Monolithic</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> Microservices</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.25"> Serverless</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.4"> Event-Driven Architecture</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> CQRS + Event Sourcing</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> DDD (Domain-Driven Design)</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.4"> Cloud-Native Architecture</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.2"> Modular Monolith</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Multi-Tenant Architecture</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> Enterprise Architecture</label>
                    </div>

                    <!-- DATABASE COMPLEXITY -->
                    <h3 class="category-title">Database complexity - پیچیدگی دیتابیس</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="complexity" data-multiplier="1.15"> پیچیدگی Relational (اسکیماهای سنگین)</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.2"> Sharding</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.25"> Replication</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Distributed Databases</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.2"> Graph DB Design</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> OLAP / Data Warehouse</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> ETL Pipelines</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Multi-DB Architecture</label>
                    </div>

                    <!-- SECURITY -->
                    <h3 class="category-title">System security - امنیت سیستم</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="complexity" data-multiplier="1.2"> OAuth2 / JWT / OIDC</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.25"> SSL/TLS Certificates</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Encryption (AES, RSA)</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.4"> OWASP Top 10 Hardening</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> Penetration Testing</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.4"> SOC2 / GDPR Compliance</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Secure Code Review</label>
                    </div>

                    <!-- PERFORMANCE -->
                    <h3 class="category-title">Performance & Scaling - عملکرد و مقیاس پذیری</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="complexity" data-multiplier="1.2"> Caching Layer (Redis/Memcached)</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.25"> CDN Integration</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Load Balancing</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.25"> Database Optimization</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.2"> Query Optimization</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Horizontal Scaling</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> High Availability Architecture</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Caching Strategy Design</label>
                    </div>

                    <!-- TESTING -->
                    <h3 class="category-title">Testing & Quality Assurance - تست و تضمین کیفیت</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="complexity" data-multiplier="1.15"> Unit Testing</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.2"> Integration Testing</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.25"> End-to-End Testing</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Load / Stress Testing</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.2"> TDD Workflow</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Automated Test Pipelines</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> Security Testing</label>
                    </div>

                    <!-- REAL-TIME -->
                    <h3 class="category-title">Real-time & Concurrent - ریئل تایمینگ و همزمانی</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="complexity" data-multiplier="1.25"> WebSocket</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Real-Time Sync</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> High Concurrency Handling</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Distributed Locking</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> Event Stream Processing</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.4"> Pub/Sub Architecture</label>
                    </div>

                    <!-- LEGACY & INTEGRATION -->
                    <h3 class="category-title">Legacy & Integration - ادغام سیستم های قدیمی</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> Work with Legacy Code</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> Third-Party Integrations</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.25"> Data Migration</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.3"> API Modernization</label>
                        <label><input type="checkbox" class="complexity" data-multiplier="1.35"> Refactoring Architecture</label>
                    </div>

                </div>

            </div>

            <!-- TAB 6: FACTORS -->
            <div id="factors" class="tab-content">
                <div class="section">
                    <h2>Factors - عوامل اثرگذار</h2>

                    <!-- Increasing Factors -->
                    <h3 class="category-title">Incremental factors - عوامل افزایشی</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.15">مهلت فشرده <br>Crunch DeadLine</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.2"> پروژه گسترده<br>Extensive Project</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.15"> استقرار چندگانه<br>Multiple Deployment</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.1"> بین المللی سازی<br>Internationalization (i18n)</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.15"> رابط کاربری/تجربه کاربری پیچیده<br>Complex UI/UX</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.2"> انطباق با مقررات<br>Regulatory Compliance</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.15"> پشتیبانی 24 ساعته<br>24/7 Support</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.25"> توسعه عملیات پیچیده<br>Complex DevOps</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.3"> مقیاس پذیری بالا<br>High Scalability</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.2"> مقاوم سازی امنیتی<br>Security Hardening</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.15"> معماری چند ابری<br>Multi-cloud Architecture</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.2"> ادغام هوش مصنوعی یا یادگیری ماشین<br>AI/ML Integration</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.18"> سیستم های پرداخت و مالی<br>Payment / Financial Systems</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.22"> ویژگی های ریئل تایم<br>Real-time Features</label>
                        <label><input type="checkbox" class="factor-plus" data-multiplier="1.25"> وب سوکت و استریمینگ<br>WebSocket / Streaming</label>
                    </div>

                    <!-- Decreasing Factors -->
                    <h3 class="category-title">Mitigating factors - عوامل کاهشی (تخفیف)</h3>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" class="factor-minus" data-multiplier="0.85">داشتن تیم بزرگ<br>Big Team</label>
                        <label><input type="checkbox" class="factor-minus" data-multiplier="0.8">استفاده از قالب های آماده<br>Usage Template</label>
                        <label><input type="checkbox" class="factor-minus" data-multiplier="0.75">استارت آپ یا پروژه کمکی<br>StartUp/Non-profit</label>
                        <label><input type="checkbox" class="factor-minus" data-multiplier="0.9">پروژه تعمیر و نگهداری<br>Maintenance Project</label>
                        <label><input type="checkbox" class="factor-minus" data-multiplier="0.85">پروژه مشابه پروژهای قبلی<br>similar project as before</label>
                        <label><input type="checkbox" class="factor-minus" data-multiplier="0.8">قرارداد بلند مدت<br>Long-term Contract</label>
                        <label><input type="checkbox" class="factor-minus" data-multiplier="0.9">وجود مستندات کامل<br>Full documentation</label>
                        <label><input type="checkbox" class="factor-minus" data-multiplier="0.85">اجزای قابل استفاده مجدد<br>Reusable Components</label>
                        <label><input type="checkbox" class="factor-minus" data-multiplier="0.88">سادگی رابط کاربری یا تجربه کاربری<br>Simplicity of UI/UX</label>
                        <label><input type="checkbox" class="factor-minus" data-multiplier="0.92">بدون نیاز به مقیاس پذیری<br>No need for scalability</label>
                    </div>

                    <!-- Deadline and Delivery -->
                    <h3 class="category-title">Deadline & Delivery - مهلت و تحویل پروژه</h3>
                    <div class="form-group">
                        <label for="deadline">مهلت تحویل:</label>
                        <select id="deadline">
                            <option value="1.0">عادی (بیشتر از 3 ماه)</option>
                            <option value="1.15">متوسط (1-3 ماه)</option>
                            <option value="1.25">فشرده (2-4 هفته)</option>
                            <option value="1.4">خیلی فشرده (کمتر از 2 هفته)</option>
                            <option value="1.55">اضطراری (1-5 روز)</option>
                        </select>
                    </div>

                    <!-- Support -->
                    <h3 class="category-title">Distribution and Support - توزیع و پشتیبانی</h3>
                    <div class="form-group">
                        <label for="support">نوع پشتیبانی:</label>
                        <select id="support">
                            <option value="1.0">عادی</option>
                            <option value="1.1">بیش‌تر از حد</option>
                            <option value="1.2">24/7</option>
                            <option value="1.3">24/7 + On-call</option>
                            <option value="1.4">Dedicated Team</option>
                        </select>
                    </div>
                </div>

            </div>

            <!-- TAB 7: RESULTS -->
            <div id="results" class="tab-content">
                <div class="result-box">
                    <h2>نتایج محاسبه</h2>

                    <div class="result-grid">
                        <div class="result-card">
                            <div class="result-label">هزینه پایه</div>
                            <div class="result-value" id="basePrice">0</div>
                            <small id="basePriceDesc"></small>
                        </div>

                        <div class="result-card">
                            <div class="result-label">ضریب تکنولوژی</div>
                            <div class="result-value" id="techMult">1.00x</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">ضریب پیچیدگی</div>
                            <div class="result-value" id="complexityMult">1.00x</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">ضریب عوامل</div>
                            <div class="result-value" id="factorMult">1.00x</div>
                        </div>

                        <div class="result-card full">
                            <div class="result-label">هزینه نهایی (تومان)</div>
                            <div class="result-value final-price" id="finalPrice">0</div>
                        </div>

                        <div class="result-card full">
                            <div class="result-label">هزینه نهایی (دلار)</div>
                            <div class="result-value final-price-dollar" id="finalPriceDollar">0 $</div>
                        </div>
                    </div>

                    <div class="breakdown-box">
                        <h3>تفکیک محاسبه</h3>
                        <div id="breakdown"></div>
                    </div>

                    <button class="btn-calculate" onclick="calculate()">محاسبه</button>
                </div>

                <div class="notes-box">
                    <h3>⚠️ نکات مهم:</h3>
                    <ul>
                        <li>قیمت ها بر اساس بازار ایران و معادل دلار محاسبه میشود</li>
                        <li>پیچیدگی معماری می‌تواند 70% به قیمت اضافه کند</li>
                        <li>Microservices و Kubernetes نیاز به متخصصان بالاتری دارند</li>
                        <li>Machine Learning و AI پرهزینه ترین پروژه ها هستند</li>
                        <li>Security و Testing باید اولویت باشند</li>
                        <li>Deadline فشرده قیمت را 40% بالا میبرد</li>
                        <li>Long-term Contract معمولا 15 تا 20 درصد تخفیف دارد</li>
                    </ul>
                </div>

            </div>

            <!-- TAB 8: Why? -->
            <div id="whyprice" class="tab-content">
                <div class="result-box">
                    <h2>چرا نباید احساس کنید قیمت ها بالا هستند!!!</h2>

                    <div class="notes-box-why">
                        <h3>⚠️ چرا قیمت پروژه های نرم افزاری «گران» نیست؟</h3>
                        <ul>
                            <li>
                                <strong>1. مقایسه با هزینه های روزمره زندگی</strong>
                                امروز در ایران:<br>
                                - یک تتو ساده روی مچ دست: حدود 5 میلیون تومان<br>
                                - یک اصلاح و رنگ مو حرفه ای: 3 تا 8 میلیون تومان<br>
                                - یک سرویس ساده خودرو: 4 تا 10 میلیون تومان<br>
                                - یک موبایل میان رده: 30 تا 50 میلیون تومان<br>
                                در مقابل:<br>
                                - یک پروژه میدلول با میانگین ساعتی 450 هزار تومان، حدود 20 ساعت زمان → جمع کل: 9 میلیون تومان<br>
                                🔹 پروژه‌ای که مستقیما برای کسب و کار درآمد ایجاد میکند، نباید ارزانتر از خدمات مصرفی کوتاه مدت تلقی شود.
                            </li>

                            <li>
                                <strong>2. نرخ دلار و تأثیر مستقیم آن بر صنعت نرم افزار</strong>
                                - دلار ≈ <?= $dollar ? $dollar : 120000 ?> تومان (در امروز)<br>
                                - تقریبا تمام ابزار های حرفه ای با دلار قیمت گذاری میشوند: سرور ها، سرویس های ابری، APIها، ابزار های توسعه، دوره های آموزشی<br>
                                - همچنین با نگاهی سطحی به اقتصاد و معشیت مردم، متوجه میشوید که در تمامی ابعاد دلار نقش دارد، از قیمت مسکن، ماشین، طلا گرفته تا قیمت مواد غذایی و تمامی موارد زندگی<br>
                                🔹 توسعه دهنده نرم‌افزار عملا در حال کار در بازار دلاری با درآمد ریالی است.
                            </li>

                            <li>
                                <strong>3. مقایسه با قیمت جهانی</strong>
                                نرخ‌های جهانی ساعتی:<br>
                                - جونیور: 35-15 دلار<br>
                                - میدل: 35-70 دلار<br>
                                - سنیور: 70-130 دلار<br>
                                - معمار: 130-190 دلار<br>
                                - متخصص: 190-250 دلار<br>
                                حتی حداقل این اعداد: 35 دلار × <?= $dollar ? $dollar : 120000 ?> ≈ <?= $dollar ? $dollar * 35 : 120000 * 35 ?>  تومان/ساعت<br>
                                در حالی که میدلول در ایران: 300 تا 600 هزار تومان → کمتر از یک دهم نرخ جهانی<br>
                                🔹 قیمت‌های فعلی، «گران» نیستند؛ به شدت تخفیف خورده‌اند.
                            </li>

                            <li>
                                <strong>4. زمان فقط کدنویسی نیست</strong>
                                - تحلیل مسئله<br>
                                - طراحی ساختار<br>
                                - تست و دیباگ<br>
                                - ارتباط با کارفرما<br>
                                - اصلاحات و پشتیبانی<br>
                                - مسئولیت خطا ها<br>
                                🔹 شما فقط برای «چند خط کد» پول نمیدهید، برای تجربه، تصمیم گیری و مسئولیت هزینه میکنید.
                            </li>

                            <li>
                                <strong>5. پروژه نرم‌افزاری هزینه نیست، سرمایه گذاری است</strong>
                                - یک تتو بعد از چند ماه فقط یک نقش است<br>
                                - یک پروژه نرم‌افزاری: فروش ایجاد میکند، هزینه‌ها را کاهش میدهد، مقیاس پذیر و قابل توسعه است<br>
                                🔹 هزینه‌ ای که بازگشت سرمایه دارد، نباید با خرج های مصرفی مقایسه شود.
                            </li>

                            <li>
                                <strong>6. قیمت پایین = ریسک بالا</strong>
                                - قیمت خیلی پایین → پروژه نیمه کاره، کد بی کیفیت، بدون پشتیبانی، دوباره‌ کاری و هزینه بیشتر<br>
                                🔹 قیمت منصفانه، در نهایت ارزان تر تمام میشود.
                            </li>

                            <li>
                                <strong>7. توسعه‌دهنده هم هزینه زندگی دارد</strong>
                                - اجاره خانه، اینترنت پایدار، برق و تجهیزات، آموزش مداوم، استهلاک سیستم<br>
                                🔹 انتظار کیفیت جهانی با دستمزد غیر واقعی، منجر به خروج نیروهای متخصص میشود.
                            </li>

                            <li>
                                <strong>8. شفافیت قیمت، به نفع همه است</strong>
                                - هدف این منشور و محاسبه گر پیشرفته: جلوگیری از دامپینگ، احترام به ارزش کار، ایجاد معیار شفاف، ارتقای کیفیت کل بازار<br>
                                🔹 قیمت منصفانه، هم به نفع کارفرماست، هم توسعه دهنده.
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div id="goup">
            ↑
        </div>

        <script src="../assets/calculator.js"></script>

        <script>
            const goUpBtn = document.getElementById("goup");

            window.addEventListener("scroll", () => {
                if (window.scrollY > 300) {
                    goUpBtn.classList.add("show");
                } else {
                    goUpBtn.classList.remove("show");
                }
            });

            goUpBtn.addEventListener("click", () => {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        </script>

</body>

</html>