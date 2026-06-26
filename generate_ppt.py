import collections
import collections.abc
from pptx import Presentation
from pptx.util import Inches, Pt
from pptx.dml.color import RGBColor
from pptx.enum.text import PP_ALIGN
from pptx.enum.shapes import MSO_SHAPE

def create_presentation():
    prs = Presentation()
    
    # Set to 16:9 widescreen
    prs.slide_width = Inches(13.333)
    prs.slide_height = Inches(7.5)
    
    # Style Variables matching InternGrowth Design System
    BG_CREAM = RGBColor(0xF4, 0xF1, 0xEA)          # #F4F1EA base cream
    BG_DARK = RGBColor(0x0F, 0x12, 0x17)           # #0F1217 base dark
    INK_DARK = RGBColor(0x0B, 0x0F, 0x14)          # #0B0F14 ink
    INK_MUTED = RGBColor(0x5C, 0x64, 0x70)         # #5C6470 muted
    INK_WHITE = RGBColor(0xF2, 0xEE, 0xE5)         # #F2EEE5 warm white
    ACCENT_TANGERINE = RGBColor(0xFF, 0x4F, 0x19)  # #FF4F19 electric tangerine
    LIME_CHARTREUSE = RGBColor(0xD4, 0xF9, 0x5E)   # #D4F95E chartreuse
    LIME_DEEP = RGBColor(0xB3, 0xDD, 0x37)         # #B3DD37 deep lime
    FOREST_DEEP = RGBColor(0x16, 0x32, 0x2F)       # #16322F forest trust
    WHITE = RGBColor(0xFF, 0xFF, 0xFF)             # card bg
    LINE_LIGHT = RGBColor(0xE5, 0xE0, 0xD3)        # light line
    LINE_DARK = RGBColor(0x1B, 0x20, 0x27)         # dark card line
    
    blank_layout = prs.slide_layouts[6]
    
    # Helper to set slide background color
    def set_bg(slide, rgb_color):
        background = slide.background
        fill = background.fill
        fill.solid()
        fill.fore_color.rgb = rgb_color
        
    # Helper to add a clean title and eyebrow
    def add_header(slide, title_text, eyebrow_text=None, dark_theme=False):
        # Eyebrow
        if eyebrow_text:
            eb_box = slide.shapes.add_textbox(Inches(1.0), Inches(0.5), Inches(11.333), Inches(0.4))
            tf_eb = eb_box.text_frame
            tf_eb.word_wrap = True
            tf_eb.margin_left = tf_eb.margin_top = tf_eb.margin_right = tf_eb.margin_bottom = 0
            p_eb = tf_eb.paragraphs[0]
            p_eb.text = eyebrow_text.upper()
            p_eb.font.name = 'Consolas'
            p_eb.font.size = Pt(11)
            p_eb.font.bold = True
            p_eb.font.color.rgb = ACCENT_TANGERINE if dark_theme else INK_MUTED
        
        # Title
        title_box = slide.shapes.add_textbox(Inches(1.0), Inches(0.8), Inches(11.333), Inches(0.9))
        tf_t = title_box.text_frame
        tf_t.word_wrap = True
        tf_t.margin_left = tf_t.margin_top = tf_t.margin_right = tf_t.margin_bottom = 0
        p_t = tf_t.paragraphs[0]
        p_t.text = title_text
        p_t.font.name = 'Trebuchet MS'
        p_t.font.size = Pt(36)
        p_t.font.bold = True
        p_t.font.color.rgb = INK_WHITE if dark_theme else INK_DARK

    # ----------------------------------------------------
    # SLIDE 1: Title Slide (Dark Theme)
    # ----------------------------------------------------
    slide1 = prs.slides.add_slide(blank_layout)
    set_bg(slide1, BG_DARK)
    
    # Eyebrow
    add_header(slide1, "", "— INTRODUCING INTERNGROWTH", dark_theme=True)
    
    # Big Title
    title_box = slide1.shapes.add_textbox(Inches(1.0), Inches(2.2), Inches(11.333), Inches(2.5))
    tf = title_box.text_frame
    tf.word_wrap = True
    p1 = tf.paragraphs[0]
    p1.text = "Your Work Becomes"
    p1.font.name = 'Trebuchet MS'
    p1.font.size = Pt(64)
    p1.font.bold = True
    p1.font.color.rgb = INK_WHITE
    
    p2 = tf.add_paragraph()
    p2.text = "Your Resume."
    p2.font.name = 'Georgia'
    p2.font.size = Pt(72)
    p2.font.italic = True
    p2.font.bold = True
    p2.font.color.rgb = ACCENT_TANGERINE
    
    # Description
    desc_box = slide1.shapes.add_textbox(Inches(1.0), Inches(5.0), Inches(8.5), Inches(1.5))
    tf_d = desc_box.text_frame
    tf_d.word_wrap = True
    p_d = tf_d.paragraphs[0]
    p_d.text = "InternGrowth is an editorial, proof-of-work marketplace where students tackle real startup tasks, earn verified experience, and build an on-chain, reputation-backed resume."
    p_d.font.name = 'Segoe UI'
    p_d.font.size = Pt(18)
    p_d.font.color.rgb = INK_WHITE
    p_d.line_spacing = 1.3
    
    # Brand underline decoration
    line = slide1.shapes.add_shape(MSO_SHAPE.RECTANGLE, Inches(1.0), Inches(6.5), Inches(2.0), Inches(0.06))
    line.fill.solid()
    line.fill.fore_color.rgb = LIME_CHARTREUSE
    line.line.color.rgb = LIME_CHARTREUSE

    # ----------------------------------------------------
    # SLIDE 2: The Problem (Cream Theme)
    # ----------------------------------------------------
    slide2 = prs.slides.add_slide(blank_layout)
    set_bg(slide2, BG_CREAM)
    add_header(slide2, "The Broken Developer Hiring Pipeline", "— 01 / THE PROBLEM")
    
    # Left Column - Students
    shape_l = slide2.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(1.0), Inches(2.0), Inches(5.3), Inches(4.5))
    shape_l.fill.solid()
    shape_l.fill.fore_color.rgb = WHITE
    shape_l.line.color.rgb = LINE_LIGHT
    
    tf_l = shape_l.text_frame
    tf_l.word_wrap = True
    tf_l.margin_left = tf_l.margin_top = tf_l.margin_right = tf_l.margin_bottom = Inches(0.4)
    
    p_lh = tf_l.paragraphs[0]
    p_lh.text = "FOR STUDENTS"
    p_lh.font.name = 'Consolas'
    p_lh.font.size = Pt(12)
    p_lh.font.bold = True
    p_lh.font.color.rgb = ACCENT_TANGERINE
    
    p_lt = tf_l.add_paragraph()
    p_lt.text = "\nThe Experience Catch-22"
    p_lt.font.name = 'Trebuchet MS'
    p_lt.font.size = Pt(22)
    p_lt.font.bold = True
    p_lt.font.color.rgb = INK_DARK
    
    bullet_l = [
        "No Experience, No Job: Students cannot get internships or roles because they lack previous 'commercial' experience.",
        "Resumes are Inflated: Text PDF resumes are packed with buzzwords, boilerplate school projects, or AI-generated exaggerations.",
        "Unfair Access: Opportunities are locked behind college brand prestige, ignoring self-taught, high-ability developers."
    ]
    for b in bullet_l:
        p = tf_l.add_paragraph()
        p.text = f"\n• {b}"
        p.font.name = 'Segoe UI'
        p.font.size = Pt(14)
        p.font.color.rgb = INK_MUTED
        p.line_spacing = 1.2

    # Right Column - Startups
    shape_r = slide2.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(7.03), Inches(2.0), Inches(5.3), Inches(4.5))
    shape_r.fill.solid()
    shape_r.fill.fore_color.rgb = WHITE
    shape_r.line.color.rgb = LINE_LIGHT
    
    tf_r = shape_r.text_frame
    tf_r.word_wrap = True
    tf_r.margin_left = tf_r.margin_top = tf_r.margin_right = tf_r.margin_bottom = Inches(0.4)
    
    p_rh = tf_r.paragraphs[0]
    p_rh.text = "FOR STARTUPS"
    p_rh.font.name = 'Consolas'
    p_rh.font.size = Pt(12)
    p_rh.font.bold = True
    p_rh.font.color.rgb = FOREST_DEEP
    
    p_rt = tf_r.add_paragraph()
    p_rt.text = "\nThe Vetting Nightmare"
    p_rt.font.name = 'Trebuchet MS'
    p_rt.font.size = Pt(22)
    p_rt.font.bold = True
    p_rt.font.color.rgb = INK_DARK
    
    bullet_r = [
        "High Vetting Overhead: Early-stage startups spend weeks sorting through hundreds of applicant profiles to find one good hire.",
        "High Risk of Bad Hires: Junior developers look great on paper but fail when tasked to touch actual, production codebases.",
        "Limited Budgets: Early-stage companies need modular tasks completed immediately but cannot afford full-time salaries."
    ]
    for b in bullet_r:
        p = tf_r.add_paragraph()
        p.text = f"\n• {b}"
        p.font.name = 'Segoe UI'
        p.font.size = Pt(14)
        p.font.color.rgb = INK_MUTED
        p.line_spacing = 1.2

    # ----------------------------------------------------
    # SLIDE 3: The Solution (Cream Theme)
    # ----------------------------------------------------
    slide3 = prs.slides.add_slide(blank_layout)
    set_bg(slide3, BG_CREAM)
    add_header(slide3, "InternGrowth: A Proof-of-Work Marketplace", "— 02 / THE SOLUTION")
    
    # Left Big Statement
    big_stat_box = slide3.shapes.add_textbox(Inches(1.0), Inches(2.2), Inches(4.5), Inches(4.0))
    tf_bs = big_stat_box.text_frame
    tf_bs.word_wrap = True
    p_bs1 = tf_bs.paragraphs[0]
    p_bs1.text = "Replacing the traditional PDF resume with a"
    p_bs1.font.name = 'Segoe UI'
    p_bs1.font.size = Pt(20)
    p_bs1.font.color.rgb = INK_MUTED
    
    p_bs2 = tf_bs.add_paragraph()
    p_bs2.text = "\nVerified Ledger of Shipped Contributions."
    p_bs2.font.name = 'Trebuchet MS'
    p_bs2.font.size = Pt(28)
    p_bs2.font.bold = True
    p_bs2.font.color.rgb = ACCENT_TANGERINE
    
    p_bs3 = tf_bs.add_paragraph()
    p_bs3.text = "\nBy executing real startup tasks, students generate undeniable proof of skill. Startups gain access to vetted, on-demand labor without standard recruiting delays."
    p_bs3.font.name = 'Segoe UI'
    p_bs3.font.size = Pt(15)
    p_bs3.font.color.rgb = INK_MUTED
    p_bs3.line_spacing = 1.3

    # Right Cards (3 stacked cards)
    card_data = [
        ("01", "Micro-Tasks with Real Stakes", "Startups slice their engineering needs into modular, self-contained tasks (e.g. APIs, UI, bug fixes)."),
        ("02", "Direct Employer Verification", "Completed work is approved, evaluated, and signed off directly by startup tech leads on the platform."),
        ("03", "Portable Skill Profile", "A dashboard showing exactly what repositories and tasks you've worked on, backed by startup references.")
    ]
    
    for i, (num, title, body) in enumerate(card_data):
        y_pos = 2.0 + (i * 1.55)
        # Card outline box
        card = slide3.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(6.0), Inches(y_pos), Inches(6.3), Inches(1.35))
        card.fill.solid()
        card.fill.fore_color.rgb = WHITE
        card.line.color.rgb = LINE_LIGHT
        
        # Text inside card
        tf_c = card.text_frame
        tf_c.word_wrap = True
        tf_c.margin_left = tf_c.margin_top = tf_c.margin_right = tf_c.margin_bottom = Inches(0.18)
        
        # We'll construct paragraphs
        p_c = tf_c.paragraphs[0]
        # Number indicator
        run_num = p_c.add_run()
        run_num.text = f"{num}  "
        run_num.font.name = 'Consolas'
        run_num.font.size = Pt(14)
        run_num.font.bold = True
        run_num.font.color.rgb = ACCENT_TANGERINE
        
        # Title
        run_t = p_c.add_run()
        run_t.text = title
        run_t.font.name = 'Trebuchet MS'
        run_t.font.size = Pt(15)
        run_t.font.bold = True
        run_t.font.color.rgb = INK_DARK
        
        # Body
        p_cb = tf_c.add_paragraph()
        p_cb.text = body
        p_cb.font.name = 'Segoe UI'
        p_cb.font.size = Pt(12)
        p_cb.font.color.rgb = INK_MUTED
        p_cb.line_spacing = 1.1

    # ----------------------------------------------------
    # SLIDE 4: The IPRS Engine (Cream Theme with Lime Highlights)
    # ----------------------------------------------------
    slide4 = prs.slides.add_slide(blank_layout)
    set_bg(slide4, BG_CREAM)
    add_header(slide4, "IPRS: InternGrowth Portfolio & Reputation Score", "— 03 / CORE INNOVATION")
    
    # Left info column
    left_box = slide4.shapes.add_textbox(Inches(1.0), Inches(2.2), Inches(5.0), Inches(4.5))
    tf_li = left_box.text_frame
    tf_li.word_wrap = True
    
    p_li1 = tf_li.paragraphs[0]
    p_li1.text = "A dynamic reputation score (0 - 100) calculated algorithmically for every student."
    p_li1.font.name = 'Segoe UI'
    p_li1.font.size = Pt(18)
    p_li1.font.color.rgb = INK_MUTED
    p_li1.line_spacing = 1.3
    
    p_li2 = tf_li.add_paragraph()
    p_li2.text = "\nTraditional hiring relies on trust. InternGrowth measures trust objectively. The IPRS score travels with students as they apply to advanced roles."
    p_li2.font.name = 'Segoe UI'
    p_li2.font.size = Pt(14)
    p_li2.font.color.rgb = INK_MUTED
    p_li2.line_spacing = 1.3
    
    p_li3 = tf_li.add_paragraph()
    p_li3.text = "\nFour Core Performance Metrics:"
    p_li3.font.name = 'Trebuchet MS'
    p_li3.font.size = Pt(16)
    p_li3.font.bold = True
    p_li3.font.color.rgb = INK_DARK
    
    metrics = [
        ("On-Time Rate", "Submission speed vs. agreed task deadlines."),
        ("Trust Score", "Ratio of accepted submissions vs. abandoned tasks."),
        ("Communication", "Collaboration feedback from startup mentors."),
        ("Satisfaction", "The code quality rating assigned by startup engineers.")
    ]
    for m_t, m_d in metrics:
        p_m = tf_li.add_paragraph()
        p_m.text = f"• {m_t}: {m_d}"
        p_m.font.name = 'Segoe UI'
        p_m.font.size = Pt(13)
        p_m.font.color.rgb = INK_MUTED

    # Right Card: The live preview card (Dark Card design)
    card_dark = slide4.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(6.8), Inches(2.0), Inches(5.5), Inches(4.5))
    card_dark.fill.solid()
    card_dark.fill.fore_color.rgb = BG_DARK
    card_dark.line.color.rgb = LINE_DARK
    
    tf_cd = card_dark.text_frame
    tf_cd.word_wrap = True
    tf_cd.margin_left = tf_cd.margin_top = tf_cd.margin_right = tf_cd.margin_bottom = Inches(0.4)
    
    p_cd1 = tf_cd.paragraphs[0]
    p_cd1.text = "LIVE · TALENT PROFILE"
    p_cd1.font.name = 'Consolas'
    p_cd1.font.size = Pt(11)
    p_cd1.font.bold = True
    p_cd1.font.color.rgb = INK_MUTED
    
    p_cd2 = tf_cd.add_paragraph()
    p_cd2.text = "\nRahul S.  [VERIFIED]"
    p_cd2.font.name = 'Trebuchet MS'
    p_cd2.font.size = Pt(24)
    p_cd2.font.bold = True
    p_cd2.font.color.rgb = INK_WHITE
    
    p_cd3 = tf_cd.add_paragraph()
    p_cd3.text = "IIT Bombay · Computer Science '27"
    p_cd3.font.name = 'Consolas'
    p_cd3.font.size = Pt(11)
    p_cd3.font.color.rgb = INK_MUTED
    
    # Internal IPRS box in Lime
    p_cd4 = tf_cd.add_paragraph()
    p_cd4.text = "\nIPRS Reputation Score"
    p_cd4.font.name = 'Consolas'
    p_cd4.font.size = Pt(12)
    p_cd4.font.color.rgb = INK_MUTED
    
    p_cd5 = tf_cd.add_paragraph()
    p_cd5.text = "94"
    p_cd5.font.name = 'Trebuchet MS'
    p_cd5.font.size = Pt(64)
    p_cd5.font.bold = True
    p_cd5.font.color.rgb = LIME_CHARTREUSE
    
    p_cd6 = tf_cd.add_paragraph()
    p_cd6.text = "Ranked #1 on Global Leaderboard  ·  Elite Tier"
    p_cd6.font.name = 'Segoe UI'
    p_cd6.font.size = Pt(12)
    p_cd6.font.color.rgb = INK_WHITE
    
    p_cd7 = tf_cd.add_paragraph()
    p_cd7.text = "\nOn-Time: 98%  |  Trust: 100%  |  Comm: 9.5/10  |  Projects: 12"
    p_cd7.font.name = 'Consolas'
    p_cd7.font.size = Pt(11)
    p_cd7.font.color.rgb = LIME_DEEP

    # ----------------------------------------------------
    # SLIDE 5: Student Flow: From Sign-up to Shipped (Cream Theme)
    # ----------------------------------------------------
    slide5 = prs.slides.add_slide(blank_layout)
    set_bg(slide5, BG_CREAM)
    add_header(slide5, "The Student Journey on InternGrowth", "— 04 / STUDENT FLOW")
    
    # 4 Steps horizontally
    step_width = Inches(2.6)
    step_spacing = Inches(0.3)
    start_x = Inches(1.0)
    
    steps = [
        ("01", "College Verification", "Students sign up and verify their college email (.edu, .ac.in). Unverified students are limited to 5 trial tasks to prevent spam."),
        ("02", "Explore & Apply", "Students browse real startup tasks. High IPRS students can lock tasks instantly, while others apply to be approved."),
        ("03", "Build & Ship", "Students complete the task inside the workspace. They submit pull requests, demo links, and time metrics directly on the portal."),
        ("04", "Earn & Level Up", "Startups review the work. On approval, escrow funds are paid to the student's wallet, and their global IPRS score increases.")
    ]
    
    for i, (num, title, text) in enumerate(steps):
        x_pos = start_x + (i * (step_width + step_spacing))
        
        # Step card
        card = slide5.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, x_pos, Inches(2.2), step_width, Inches(4.2))
        card.fill.solid()
        card.fill.fore_color.rgb = WHITE
        card.line.color.rgb = LINE_LIGHT
        
        tf_s = card.text_frame
        tf_s.word_wrap = True
        tf_s.margin_left = tf_s.margin_top = tf_s.margin_right = tf_s.margin_bottom = Inches(0.25)
        
        p_num = tf_s.paragraphs[0]
        p_num.text = num
        p_num.font.name = 'Consolas'
        p_num.font.size = Pt(28)
        p_num.font.bold = True
        p_num.font.color.rgb = ACCENT_TANGERINE
        
        p_t = tf_s.add_paragraph()
        p_t.text = title
        p_t.font.name = 'Trebuchet MS'
        p_t.font.size = Pt(16)
        p_t.font.bold = True
        p_t.font.color.rgb = INK_DARK
        
        p_tx = tf_s.add_paragraph()
        p_tx.text = f"\n{text}"
        p_tx.font.name = 'Segoe UI'
        p_tx.font.size = Pt(12)
        p_tx.font.color.rgb = INK_MUTED
        p_tx.line_spacing = 1.2

    # ----------------------------------------------------
    # SLIDE 6: Startup & Admin Operations (Cream Theme)
    # ----------------------------------------------------
    slide6 = prs.slides.add_slide(blank_layout)
    set_bg(slide6, BG_CREAM)
    add_header(slide6, "Decentralized Operations & Moderation", "— 05 / SYSTEM ROLES")
    
    # Left Column: Startups
    shape_s = slide6.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(1.0), Inches(2.0), Inches(5.3), Inches(4.5))
    shape_s.fill.solid()
    shape_s.fill.fore_color.rgb = WHITE
    shape_s.line.color.rgb = LINE_LIGHT
    
    tf_s = shape_s.text_frame
    tf_s.word_wrap = True
    tf_s.margin_left = tf_s.margin_top = tf_s.margin_right = tf_s.margin_bottom = Inches(0.4)
    
    p_sh = tf_s.paragraphs[0]
    p_sh.text = "STARTUP WORKFLOW"
    p_sh.font.name = 'Consolas'
    p_sh.font.size = Pt(12)
    p_sh.font.bold = True
    p_sh.font.color.rgb = FOREST_DEEP
    
    bullets_s = [
        "Verify Business: Startups request admin verification to prevent fake projects.",
        "Fund Escrow: Startups deposit funds to their wallet before a task goes live, guaranteeing student payment.",
        "Review Work: Direct UI dashboard to review students' pull requests and host codes.",
        "Recruit Direct: Startups can filter the leaderboard by IPRS score to recruit top performers for internships or jobs."
    ]
    for b in bullets_s:
        p = tf_s.add_paragraph()
        p.text = f"\n• {b}"
        p.font.name = 'Segoe UI'
        p.font.size = Pt(14)
        p.font.color.rgb = INK_MUTED
        p.line_spacing = 1.2

    # Right Column: Admin
    shape_a = slide6.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(7.03), Inches(2.0), Inches(5.3), Inches(4.5))
    shape_a.fill.solid()
    shape_a.fill.fore_color.rgb = WHITE
    shape_a.line.color.rgb = LINE_LIGHT
    
    tf_a = shape_a.text_frame
    tf_a.word_wrap = True
    tf_a.margin_left = tf_a.margin_top = tf_a.margin_right = tf_a.margin_bottom = Inches(0.4)
    
    p_ah = tf_a.paragraphs[0]
    p_ah.text = "ADMIN MODERATION"
    p_ah.font.name = 'Consolas'
    p_ah.font.size = Pt(12)
    p_ah.font.bold = True
    p_ah.font.color.rgb = ACCENT_TANGERINE
    
    bullets_a = [
        "Vetting Dashboard: Dedicated admin views to approve or reject startup registration requests.",
        "Wallet Escrow Checks: High-level overview of deposits, pending tasks, and global wallet ledger metrics.",
        "Student Moderation: Track reported tasks or flag students engaged in academic dishonesty/cheating.",
        "Global Analytics: View the system metrics (Task success rates, average time to deliver, active funding)."
    ]
    for b in bullets_a:
        p = tf_a.add_paragraph()
        p.text = f"\n• {b}"
        p.font.name = 'Segoe UI'
        p.font.size = Pt(14)
        p.font.color.rgb = INK_MUTED
        p.line_spacing = 1.2

    # ----------------------------------------------------
    # SLIDE 7: Key Features Implemented (Cream Theme)
    # ----------------------------------------------------
    slide7 = prs.slides.add_slide(blank_layout)
    set_bg(slide7, BG_CREAM)
    add_header(slide7, "Platform Capabilities & Codebase Features", "— 06 / FEATURES BUILT")
    
    # 3 columns for 3 features
    col_width = Inches(3.5)
    col_spacing = Inches(0.4)
    start_x = Inches(1.0)
    
    features = [
        ("Verification Core", "A custom verification engine validating student emails against academic domains (.edu, .ac.in). Unverified users have a hard 5-task limit. Admins manually verify all business registrations."),
        ("Integrated Wallet", "Supports escrow funding. Startups fund task wallets. Funds are held in escrow, then released to student wallets on task approval. Allows admins manual credits & withdrawals."),
        ("Reputation Engine", "Calculates overall IPRS score dynamically. Aggregates delivery time, communication rating, and founder satisfaction. Renders live badges (Elite, Pro, Rising) on profiles.")
    ]
    
    for i, (title, text) in enumerate(features):
        x_pos = start_x + (i * (col_width + col_spacing))
        
        card = slide7.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, x_pos, Inches(2.2), col_width, Inches(4.2))
        card.fill.solid()
        card.fill.fore_color.rgb = WHITE
        card.line.color.rgb = LINE_LIGHT
        
        tf_f = card.text_frame
        tf_f.word_wrap = True
        tf_f.margin_left = tf_f.margin_top = tf_f.margin_right = tf_f.margin_bottom = Inches(0.3)
        
        # Title of feature
        p_t = tf_f.paragraphs[0]
        p_t.text = title
        p_t.font.name = 'Trebuchet MS'
        p_t.font.size = Pt(18)
        p_t.font.bold = True
        p_t.font.color.rgb = FOREST_DEEP
        
        # Underline
        p_u = tf_f.add_paragraph()
        p_u.text = "—"
        p_u.font.name = 'Consolas'
        p_u.font.size = Pt(12)
        p_u.font.color.rgb = ACCENT_TANGERINE
        
        # Text
        p_tx = tf_f.add_paragraph()
        p_tx.text = f"\n{text}"
        p_tx.font.name = 'Segoe UI'
        p_tx.font.size = Pt(13)
        p_tx.font.color.rgb = INK_MUTED
        p_tx.line_spacing = 1.35

    # ----------------------------------------------------
    # SLIDE 8: Technology Stack (Cream Theme)
    # ----------------------------------------------------
    slide8 = prs.slides.add_slide(blank_layout)
    set_bg(slide8, BG_CREAM)
    add_header(slide8, "Our Robust, Production-Ready Stack", "— 07 / TECH ARCHITECTURE")
    
    # Left column: Architecture components
    arch_box = slide8.shapes.add_textbox(Inches(1.0), Inches(2.0), Inches(5.5), Inches(4.5))
    tf_ac = arch_box.text_frame
    tf_ac.word_wrap = True
    
    p_ac1 = tf_ac.paragraphs[0]
    p_ac1.text = "Larval (PHP) Model-View-Controller"
    p_ac1.font.name = 'Trebuchet MS'
    p_ac1.font.size = Pt(22)
    p_ac1.font.bold = True
    p_ac1.font.color.rgb = INK_DARK
    
    bullets_ac = [
        "Backend Engine: Powered by Laravel 11. Implements controllers, request validations, service layers, database migrations, and mail queues.",
        "Secure Database: Relational schema using PostgreSQL / MySQL. Handles cascading deletes, foreign keys, constraints, and audit-friendly ledgers.",
        "Security & Authentication: Email verification validation, session-based authentications, and password hashing standard.",
        "Blade Templates: Styled using CSS variables. Pure editorial aesthetics with custom layouts, custom CSS animations, and responsiveness."
    ]
    for b in bullets_ac:
        p = tf_ac.add_paragraph()
        p.text = f"\n• {b}"
        p.font.name = 'Segoe UI'
        p.font.size = Pt(13)
        p.font.color.rgb = INK_MUTED
        p.line_spacing = 1.25

    # Right column: Stack details
    stack_card = slide8.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(7.0), Inches(2.0), Inches(5.333), Inches(4.5))
    stack_card.fill.solid()
    stack_card.fill.fore_color.rgb = FOREST_DEEP
    stack_card.line.color.rgb = FOREST_DEEP
    
    tf_sc = stack_card.text_frame
    tf_sc.word_wrap = True
    tf_sc.margin_left = tf_sc.margin_top = tf_sc.margin_right = tf_sc.margin_bottom = Inches(0.4)
    
    p_sch = tf_sc.paragraphs[0]
    p_sch.text = "STACK SPECIFICATIONS"
    p_sch.font.name = 'Consolas'
    p_sch.font.size = Pt(12)
    p_sch.font.bold = True
    p_sch.font.color.rgb = LIME_CHARTREUSE
    
    techs = [
        ("FRAMEWORK", "Laravel 11.x (PHP 8.2+)"),
        ("DATABASE", "PostgreSQL / MySQL (Neon Serverless Db)"),
        ("FRONTEND", "Blade Template Engine, CSS Design System, Tailwind"),
        ("WALLET SYSTEM", "Virtual ledger, Secure escrow tables"),
        ("AI GRADER", "Optional integration API for code assessment"),
        ("MAIL SERVICE", "SMTP Mail Trap, College domain verification checks")
    ]
    for cat, val in techs:
        p_cat = tf_sc.add_paragraph()
        p_cat.text = f"\n{cat}"
        p_cat.font.name = 'Consolas'
        p_cat.font.size = Pt(11)
        p_cat.font.bold = True
        p_cat.font.color.rgb = INK_WHITE
        
        p_val = tf_sc.add_paragraph()
        p_val.text = val
        p_val.font.name = 'Segoe UI'
        p_val.font.size = Pt(13)
        p_val.font.color.rgb = LIME_DEEP

    # ----------------------------------------------------
    # SLIDE 9: Why InternGrowth Wins (Cream Theme)
    # ----------------------------------------------------
    slide9 = prs.slides.add_slide(blank_layout)
    set_bg(slide9, BG_CREAM)
    add_header(slide9, "Why InternGrowth Wins the Hackathon", "— 08 / THE PITCH")
    
    # 3 horizontal columns
    w = Inches(3.5)
    s = Inches(0.4)
    start_x = Inches(1.0)
    
    pitch_points = [
        ("Solves a Real Pain-Point", "It bridges the massive experience gap for students and reduces vetting risks/costs for cash-strapped early-stage startups."),
        ("Proof over Credentials", "We eliminate CV fraud. A student's rank is based entirely on actual, verified performance, not university brand name."),
        ("Frictionless Onboarding", "Vetted workflows, automated portfolios, simple tasks, and integrated digital wallets make it extremely easy to start immediately.")
    ]
    
    for i, (title, body) in enumerate(pitch_points):
        x_pos = start_x + (i * (w + s))
        
        card = slide9.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, x_pos, Inches(2.2), w, Inches(4.2))
        card.fill.solid()
        card.fill.fore_color.rgb = WHITE
        card.line.color.rgb = LINE_LIGHT
        
        tf_p = card.text_frame
        tf_p.word_wrap = True
        tf_p.margin_left = tf_p.margin_top = tf_p.margin_right = tf_p.margin_bottom = Inches(0.35)
        
        p_num = tf_p.paragraphs[0]
        p_num.text = f"0{i+1}"
        p_num.font.name = 'Consolas'
        p_num.font.size = Pt(20)
        p_num.font.bold = True
        p_num.font.color.rgb = ACCENT_TANGERINE
        
        p_t = tf_p.add_paragraph()
        p_t.text = title
        p_t.font.name = 'Trebuchet MS'
        p_t.font.size = Pt(17)
        p_t.font.bold = True
        p_t.font.color.rgb = INK_DARK
        
        p_b = tf_p.add_paragraph()
        p_b.text = f"\n{body}"
        p_b.font.name = 'Segoe UI'
        p_b.font.size = Pt(13)
        p_b.font.color.rgb = INK_MUTED
        p_b.line_spacing = 1.3

    # ----------------------------------------------------
    # SLIDE 10: Conclusion & Next Steps (Dark Theme)
    # ----------------------------------------------------
    slide10 = prs.slides.add_slide(blank_layout)
    set_bg(slide10, BG_DARK)
    
    add_header(slide10, "", "— CONCLUSION", dark_theme=True)
    
    # Large Text
    title_box = slide10.shapes.add_textbox(Inches(1.0), Inches(2.0), Inches(11.333), Inches(2.0))
    tf_c = title_box.text_frame
    tf_c.word_wrap = True
    
    p_c1 = tf_c.paragraphs[0]
    p_c1.text = "Build. Verify. Grow."
    p_c1.font.name = 'Trebuchet MS'
    p_c1.font.size = Pt(64)
    p_c1.font.bold = True
    p_c1.font.color.rgb = INK_WHITE
    
    p_c2 = tf_c.add_paragraph()
    p_c2.text = "The New Credential Layer for the Student Developer Economy."
    p_c2.font.name = 'Georgia'
    p_c2.font.size = Pt(28)
    p_c2.font.italic = True
    p_c2.font.color.rgb = LIME_CHARTREUSE
    
    # Information Box
    info_box = slide10.shapes.add_textbox(Inches(1.0), Inches(4.5), Inches(11.333), Inches(2.0))
    tf_i = info_box.text_frame
    tf_i.word_wrap = True
    
    p_i1 = tf_i.paragraphs[0]
    p_i1.text = "Check out InternGrowth live on our server or browse tasks locally!"
    p_i1.font.name = 'Segoe UI'
    p_i1.font.size = Pt(16)
    p_i1.font.color.rgb = INK_WHITE
    
    p_i2 = tf_i.add_paragraph()
    p_i2.text = "\nThank You!  |  Q&A Session"
    p_i2.font.name = 'Segoe UI'
    p_i2.font.size = Pt(18)
    p_i2.font.bold = True
    p_i2.font.color.rgb = ACCENT_TANGERINE
    
    p_i3 = tf_i.add_paragraph()
    p_i3.text = "Github: github.com/interngrowth  ·  Website: interngrowth.dev"
    p_i3.font.name = 'Consolas'
    p_i3.font.size = Pt(12)
    p_i3.font.color.rgb = INK_MUTED
    
    # Save Presentation
    prs.save("c:/Users/Raval Ruchit/Desktop/interndesign/InternGrowth/InternGrowth_Presentation.pptx")
    print("Presentation saved successfully at c:/Users/Raval Ruchit/Desktop/interndesign/InternGrowth/InternGrowth_Presentation.pptx")

if __name__ == '__main__':
    create_presentation()
