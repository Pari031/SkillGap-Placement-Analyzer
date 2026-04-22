<?php 
$current_page = basename($_SERVER['PHP_SELF']);
$is_app_page = isset($_SESSION['user_id']) && !in_array($current_page, ['index.php', 'login.php', 'register.php']);
?>

<?php if ($is_app_page): ?>
            </div> </main> </div> <?php endif; ?>

<footer class="footer" id="contact" style="<?= $is_app_page ? 'margin-left: var(--sidebar-width); width: calc(100% - var(--sidebar-width));' : 'width: 100%;'; ?> background: white; border-top: 1px solid var(--border-color); padding: 4rem 0 2rem; transition: all 0.3s ease;">
    <div class="container" style="width: 90%; max-width: 1200px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 50px; text-align: left;">
            <div>
                <h4 style="color: var(--secondary); margin-bottom: 1.2rem; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-rocket" style="color: var(--primary);"></i> SkillGap AI
                </h4>
                <p style="font-size: 0.9rem; color: #64748b; line-height: 1.7; margin-bottom: 1.5rem;">
                    Bridging the gap between student potential and industry demand through NLP-driven resume extraction and predictive readiness scoring.
                </p>
                <div style="display: flex; gap: 15px; color: var(--primary);">
                    <a href="#" style="color: inherit;"><i class="fab fa-linkedin fa-lg"></i></a>
                    <a href="#" style="color: inherit;"><i class="fab fa-github fa-lg"></i></a>
                    <a href="#" style="color: inherit;"><i class="fab fa-instagram fa-lg"></i></a>
                </div>
            </div>

            <div>
                <h4 style="color: var(--secondary); margin-bottom: 1.2rem;">Placement Hub</h4>
                <ul style="list-style: none; padding: 0; font-size: 0.85rem; color: #64748b;">
                    <li style="margin-bottom: 10px;"><i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px; color: var(--accent);"></i> AI Readiness Score</li>
                    <li style="margin-bottom: 10px;"><i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px; color: var(--accent);"></i> Skill Gap Analysis</li>
                    <li style="margin-bottom: 10px;"><i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px; color: var(--accent);"></i> Real-time Job Matching</li>
                    <li style="margin-bottom: 10px;"><i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px; color: var(--accent);"></i> Smart Clustering</li>
                </ul>
            </div>

            <div>
                <h4 style="color: var(--secondary); margin-bottom: 1.2rem;">Get in Touch</h4>
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 10px;">
                    <i class="fas fa-envelope-open-text" style="color: var(--primary); margin-right: 10px;"></i> support.placement@niet.co.in
                </p>
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 10px;">
                    <i class="fas fa-map-marker-alt" style="color: var(--primary); margin-right: 10px;"></i> NIET, Knowledge Park II, Greater Noida
                </p>
                <p style="font-size: 0.85rem; color: #64748b;">
                    <i class="fas fa-phone-volume" style="color: var(--primary); margin-right: 10px;"></i> +91 0120 232 XXXX
                </p>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 3rem 0 1.5rem;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <p style="font-size: 0.8rem; color: #94a3b8; font-weight: 500;">
                &copy; <?= date("Y"); ?> SkillGap Placement Portal. All rights reserved.
            </p>
            <div style="font-size: 0.75rem; color: #94a3b8;">
                Developed by <strong>Pari</strong> | B.Tech CSE-AI 2nd Year
            </div>
        </div>
    </div>
</footer>
</body>
</html>